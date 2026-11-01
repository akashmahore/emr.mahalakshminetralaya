#!/bin/bash
# Mahalakshmi Netralaya - Production Deployment Script
# Usage: ./deploy.sh [environment] [version]

set -euo pipefail

# Configuration
ENVIRONMENT=${1:-production}
VERSION=${2:-latest}
PROJECT_NAME="mahalakshmi-netralaya"
DOMAIN="mahalakshminetralaya.com"

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

log() {
    echo -e "${BLUE}[$(date +'%Y-%m-%d %H:%M:%S')]${NC} $1"
}

success() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] ✅ $1${NC}"
}

warning() {
    echo -e "${YELLOW}[$(date +'%Y-%m-%d %H:%M:%S')] ⚠️  $1${NC}"
}

error() {
    echo -e "${RED}[$(date +'%Y-%m-%d %H:%M:%S')] ❌ $1${NC}"
}

# Check prerequisites
check_prerequisites() {
    log "Checking prerequisites..."

    command -v docker >/dev/null 2>&1 || { error "Docker is not installed"; exit 1; }
    command -v docker-compose >/dev/null 2>&1 || { error "Docker Compose is not installed"; exit 1; }

    # Check if .env file exists
    if [ ! -f .env ]; then
        error ".env file not found! Copy .env.production to .env and configure it."
        exit 1
    fi

    # Check required environment variables
    source .env
    required_vars=("APP_KEY" "DB_PASSWORD" "REDIS_PASSWORD" "MAIL_PASSWORD" "AWS_ACCESS_KEY_ID" "AWS_SECRET_ACCESS_KEY" "SENTRY_LARAVEL_DSN")
    for var in "${required_vars[@]}"; do
        if [ -z "${!var:-}" ]; then
            warning "Environment variable $var is not set"
        fi
    done

    success "Prerequisites check passed"
}

# Pull latest images
pull_images() {
    log "Pulling latest Docker images..."
    docker-compose -f docker-compose.prod.yml pull
    success "Images pulled"
}

# Backup database before deployment
backup_database() {
    log "Creating pre-deployment database backup..."
    BACKUP_FILE="backups/pre-deploy-$(date +%Y%m%d-%H%M%S).sql.gz"

    docker-compose -f docker-compose.prod.yml exec -T db \
        mysqldump -u root -p"$DB_ROOT_PASSWORD" \
        --single-transaction --routines --triggers --events \
        mahalakshmi_netralaya | gzip > "$BACKUP_FILE"

    if [ $? -eq 0 ]; then
        success "Database backup created: $BACKUP_FILE"
    else
        error "Database backup failed!"
        exit 1
    fi
}

# Deploy application
deploy_app() {
    log "Deploying application..."

    # Build and start containers
    docker-compose -f docker-compose.prod.yml up -d --build --remove-orphans

    # Wait for services to be healthy
    log "Waiting for services to be healthy..."
    sleep 30

    # Check health
    for i in {1..10}; do
        if docker-compose -f docker-compose.prod.yml exec -T app php -r "exit(0);" 2>/dev/null; then
            success "Application is healthy"
            break
        fi
        if [ $i -eq 10 ]; then
            error "Application health check failed after 10 attempts"
            exit 1
        fi
        sleep 10
    done
}

# Run migrations
run_migrations() {
    log "Running database migrations..."
    docker-compose -f docker-compose.prod.yml exec -T app php artisan migrate --force
    success "Migrations completed"
}

# Clear caches
clear_caches() {
    log "Clearing caches..."
    docker-compose -f docker-compose.prod.yml exec -T app php artisan config:clear
    docker-compose -f docker-compose.prod.yml exec -T app php artisan route:clear
    docker-compose -f docker-compose.prod.yml exec -T app php artisan view:clear
    docker-compose -f docker-compose.prod.yml exec -T app php artisan cache:clear

    # Rebuild optimized caches
    docker-compose -f docker-compose.prod.yml exec -T app php artisan config:cache
    docker-compose -f docker-compose.prod.yml exec -T app php artisan route:cache
    docker-compose -f docker-compose.prod.yml exec -T app php artisan view:cache
    docker-compose -f docker-compose.prod.yml exec -T app php artisan event:cache

    success "Caches rebuilt"
}

# Health check
health_check() {
    log "Running health checks..."

    # Check HTTP endpoint
    for i in {1..5}; do
        if curl -f -s "https://$DOMAIN/health" >/dev/null 2>&1; then
            success "Health endpoint responding"
            break
        fi
        if [ $i -eq 5 ]; then
            error "Health check failed"
            exit 1
        fi
        sleep 10
    done

    # Check database connection
    if docker-compose -f docker-compose.prod.yml exec -T app php artisan tinker --execute="DB::connection()->getPdo(); echo 'DB OK';" 2>/dev/null | grep -q "DB OK"; then
        success "Database connection OK"
    else
        error "Database connection failed"
        exit 1
    fi

    # Check Redis connection
    if docker-compose -f docker-compose.prod.yml exec -T app php artisan tinker --execute="Redis::connection()->ping(); echo 'Redis OK';" 2>/dev/null | grep -q "Redis OK"; then
        success "Redis connection OK"
    else
        error "Redis connection failed"
        exit 1
    fi
}

# Post-deployment tasks
post_deploy() {
    log "Running post-deployment tasks..."

    # Restart queue workers
    docker-compose -f docker-compose.prod.yml restart horizon

    # Restart scheduler
    docker-compose -f docker-compose.prod.yml restart scheduler

    # Warm up caches
    curl -s "https://$DOMAIN" >/dev/null
    curl -s "https://$DOMAIN/dashboard" >/dev/null

    success "Post-deployment tasks completed"
}

# Rollback function
rollback() {
    error "Deployment failed! Initiating rollback..."

    # Restore database
    if [ -f "$BACKUP_FILE" ]; then
        log "Restoring database from backup..."
        gunzip -c "$BACKUP_FILE" | docker-compose -f docker-compose.prod.yml exec -T db mysql -u root -p"$DB_ROOT_PASSWORD" mahalakshmi_netralaya
        success "Database restored"
    fi

    # Restart previous containers
    docker-compose -f docker-compose.prod.yml down
    docker-compose -f docker-compose.prod.yml up -d

    error "Rollback completed. Please investigate the issue."
    exit 1
}

# Main deployment flow
main() {
    echo "=========================================="
    echo "Mahalakshmi Netralaya - Production Deploy"
    echo "Environment: $ENVIRONMENT"
    echo "Version: $VERSION"
    echo "=========================================="

    check_prerequisites

    # Trap errors for rollback
    trap rollback ERR

    pull_images
    backup_database
    deploy_app
    run_migrations
    clear_caches
    health_check
    post_deploy

    success "=========================================="
    success "Deployment completed successfully! 🎉"
    success "Application: https://$DOMAIN"
    success "=========================================="
}

# Run main function
main "$@"