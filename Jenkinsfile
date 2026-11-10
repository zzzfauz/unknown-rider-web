pipeline {
    agent any

    environment {
        PATH = "C:\\php84;C:\\ProgramData\\ComposerSetup\\bin;${env.PATH}"
    }

    stages {
        stage('Checkout') {
            steps {
                echo 'Mengambil source code Unknown Rider'
                checkout scm
            }
        }

        stage('Check Environment') {
            steps {
                bat 'php --version'
                bat 'composer --version'
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