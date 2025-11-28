pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/pgnaik/php-mysql-crud-app.git'
            }
        }

        stage('PHP Syntax Check') {
            steps {
                bat 'php -l src/api.php'
                bat 'php -l src/db.php'
            }
        }

        stage('Build and Deploy with Docker Compose') {
            steps {
                bat 'docker compose down'
                bat 'docker compose build'
                bat 'docker compose up -d'
            }
        }
    }

    post {
        success {
            echo "App deployed! Open: http://<jenkins-server-ip>:8080"
        }
        failure {
            echo "Build or deployment failed."
        }
    }
}
