/**
 * Jenkinsfile for building, pushing, and deploying the PHP application.
 *
 * This pipeline assumes:
 * 1. A Docker daemon is available on the Jenkins agent.
 * 2. Access to a self-hosted Harbor registry.
 * 3. Access to a Kubernetes cluster.
 * 4. Necessary credentials (Harbor, Kubeconfig) are configured in Jenkins.
 */

pipeline {
    agent any // You might want to use a specific agent with Docker/kubectl installed

    environment {
        // Define application and registry variables
        // IMPORTANT: Replace with your actual Harbor registry URL and project
        HARBOR_REGISTRY = 'your-harbor-registry.example.com' // e.g., harbor.mycompany.com
        HARBOR_PROJECT = 'your-harbor-project'             // e.g., my-php-app
        IMAGE_NAME = "${HARBOR_REGISTRY}/${HARBOR_PROJECT}/php-mysql-app"
        IMAGE_TAG = "latest" // Or use Git commit hash: "git rev-parse --short HEAD"
        K8S_NAMESPACE = "default" // Or your specific Kubernetes namespace
        K8S_DEPLOYMENT_NAME = "php-mysql-app-deployment"
    }

    stages {
        stage('Checkout') {
            steps {
                // Checkout the source code from the SCM (e.g., Git)
                git branch: 'main', url: 'https://github.com/your-username/your-repo.git' // Replace with your repo URL
            }
        }

        stage('Build Docker Image') {
            steps {
                script {
                    // Build the Docker image
                    // The Dockerfile is expected in the root of the repository
                    sh "docker build -t ${IMAGE_NAME}:${IMAGE_TAG} ."
                }
            }
        }

        stage('Push Docker Image to Harbor') {
            steps {
                script {
                    // Authenticate to Harbor using Jenkins credentials
                    // 'harbor-credentials' should be a 'Username with password' credential in Jenkins
                    // where Username is your Harbor username and Password is your Harbor password.
                    withCredentials([usernamePassword(credentialsId: 'harbor-credentials', passwordVariable: 'HARBOR_PASSWORD', usernameVariable: 'HARBOR_USERNAME')]) {
                        sh "docker login ${HARBOR_REGISTRY} -u ${HARBOR_USERNAME} -p ${HARBOR_PASSWORD}"
                        sh "docker push ${IMAGE_NAME}:${IMAGE_TAG}"
                        sh "docker logout ${HARBOR_REGISTRY}" // Good practice to logout
                    }
                }
            }
        }

        stage('Deploy to Kubernetes') {
            steps {
                script {
                    // Deploy to Kubernetes using kubectl
                    // 'kubeconfig-credentials' should be a 'Secret file' credential in Jenkins
                    // containing your Kubernetes kubeconfig file.
                    withCredentials([file(credentialsId: 'kubeconfig-credentials', variable: 'KUBECONFIG_FILE_PATH')]) {
                        // Set KUBECONFIG environment variable for kubectl to use the provided file
                        sh "KUBECONFIG=${KUBECONFIG_FILE_PATH} kubectl config use-context your-kubernetes-context" // Replace with your K8s context name
                        sh "KUBECONFIG=${KUBECONFIG_FILE_PATH} kubectl apply -f kubernetes/deployment.yaml -n ${K8S_NAMESPACE}"
                        sh "KUBECONFIG=${KUBECONFIG_FILE_PATH} kubectl set image deployment/${K8S_DEPLOYMENT_NAME} php-mysql-app=${IMAGE_NAME}:${IMAGE_TAG} -n ${K8S_NAMESPACE}"
                        // Optionally, wait for rollout to complete
                        sh "KUBECONFIG=${KUBECONFIG_FILE_PATH} kubectl rollout status deployment/${K8S_DEPLOYMENT_NAME} -n ${K8S_NAMESPACE}"
                    }
                }
            }
        }
    }

    post {
        always {
            // Clean up Docker images on the Jenkins agent to save disk space
            sh "docker rmi ${IMAGE_NAME}:${IMAGE_TAG} || true" // '|| true' to prevent failure if image not found
        }
        failure {
            echo "Pipeline failed. Check logs for details."
            // Add notifications here (e.g., email, Slack)
        }
        success {
            echo "Pipeline completed successfully!"
        }
    }
}
