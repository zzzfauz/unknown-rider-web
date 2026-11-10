pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                echo 'Mengambil source code Unknown Rider'
                checkout scm
            }
        }

        stage('Install Dependencies') {
            steps {
                bat 'composer install --no-interaction --prefer-dist'
            }
        }

        stage('Run Laravel Tests') {
            steps {
                bat 'php artisan test'
            }
        }
    }

    post {
        success {
            echo 'CI Pipeline berhasil!'
        }

        failure {
            echo 'CI Pipeline gagal. Periksa log pengujian.'
        }
    }
}