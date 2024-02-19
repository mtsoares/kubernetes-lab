# Lab 05

This lab will show how to omanage a Kubernetes deployment, creating and scaling an app called demoapp.

## Preparation

Ask your instructor for the following information:

* demoapp FQDN for Ingress configuration
* demoapp image URL
* demoapp registry login credentials and server

demoapp uses the following environment variables for configuration:

DATABASE_SERVER: mysql database server name already provided by the instructo
DATABASE_USERNAME: demoappuser
DATABASE_PASSWORD: securepassword
DATABASE_NAME: demoappdb

All 4 environment variables must be configured at the deployment on this lab.

## Create a namespace 

Create a namespace for the new app and switch to it:

```bash
kubectl create namespace demoapp
kubectl config set-context --current --namespace=demoapp
```

As the demoapp image is hosted on a container registry that is not the Docker Hub, and it is protected by password authentication, it is needed to explicitly tell any pod the credentials to login. To do so, use the following command to create a Docker Registry secret:

```bash
kubectl create secret docker-registry \<PROVIDE A NAME FOR THE SECRET\> --docker-server=\<DOCKER_REGISTRY_SERVER\> --docker-username=\<DOCKER_REGISTRY_USERNAME\> --docker-password=\<DOCKER_REGISTRY_PASSWORD\>
```

Check if the secret have been created with "kubectl get secrets" and write down its name.

Change to Lab05 folder and edit the demoapp-deployment.yml file:

```bash
cd ~/kubernetes-lab/Lab05
nano demoapp-depoyment.yml
```

Configure it adding the required information on the file. Then apply the file and check if the deployment and pods have started correctly:

```bash
kubectl get deployments
kubectl get pods
```

## Enabling external access to demoapp

Create a ClusterIP service for demoapp editing the demoapp-service.yml file, and then apply the service file.

Create a Ingress for demoapp editing the demoapp-ingress.yml file, and then apply the ingress file.

