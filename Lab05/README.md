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

Test the application by opening a browser and accessing http://\<DEMOAPP_FQDN\>/demoapp.php. This application shows some information that will be useful for troubleshooting and visibility:

* The environment variables provided
* The name of the pod running the container
* A list of students added to the MySQL database being connected.

## Scale up the deployment

Edit the demoapp-deployment.yml file and change the replica amount from 1 to 3. Apply the file and:

* Check if the number of replicas changed using "kubectl get deployments"
* Refresh the browser and check the Pod field. It should change to reflect from what pod the application is running from.
* Check the pod names doing a "kubectl get pods" and check if the pod names are the same visible on the application.

## Create a configMap for common configurations

ConfigMaps are useful to reuse the same configuration accross several pods and deployments. On demoapp, if several apps were running o the same cluster and the database server was always the same, would be interesting to centralize it configuration.

Edit the demoapp-configmap.yml file and add the requested information about the databsae server on the file. Apply the file and check if the configmap is created:

```bash
kubectl get configmaps
```

Edit the demoapp-deployment.yml and remove the environment variable DATABASE_SERVER and its value. Add the following under the container configuration (right below the image configuration):

```yaml
        envFrom:
        - configMapRef:
            name: demoapp-configmap
```

Apply the configuration again and check if demoapp application still works.

