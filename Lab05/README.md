# Lab 05

This lab will show how to manage a Kubernetes deployment, creating and scaling an app called "demoapp".

## Preparation

Ask your instructor for the following information:

* An FQDN for the demoapp, for Ingress configuration.
* A demoapp image URL.
* Credentials and server for the demoapp registry.

The demoapp uses the following environment variables for configuration:

* DATABASE_SERVER: MySQL Database server name (already provided by the instructor on Lab 02).
* DATABASE_USERNAME: demoappuser
* DATABASE_PASSWORD: securepassword
* DATABASE_NAME: demoappdb

All four environment variables must be configured at the deployment on this lab.

## Creating a namespace 

Create a namespace for the new app and switch to it:

```bash
kubectl create namespace demoapp
kubectl config set-context --current --namespace=demoapp
```

As the demoapp image is hosted on a container registry that is not the Docker Hub, and it is protected by password authentication, you need to explicitly tell any pod the credentials to login. To do so, use the following command to create a Docker Registry secret:

```bash
kubectl create secret docker-registry \<PROVIDE A NAME FOR THE SECRET\> --docker-server=\<DOCKER_REGISTRY_SERVER\> --docker-username=\<DOCKER_REGISTRY_USERNAME\> --docker-password=\<DOCKER_REGISTRY_PASSWORD\>
```

Check if the secret was created with "kubectl get secrets" and write down its name.

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

* The environment variables provided.
* The name of the pod running the container.
* A list of students added to the MySQL database being connected.

## Scaling up the deployment

Edit the demoapp-deployment.yml file and change the replica amount from 1 to 3. Apply the file and:

* Check if the number of replicas has changed using "kubectl get deployments".
* Refresh the browser and check the Pod field. It should change to reflect which pod the application is running from.
* Check the names of the pods doing a "kubectl get pods" and check if the pod names match those you see in the application.

## Creating a configMap for common configurations

ConfigMaps are useful to reuse the same configuration accross several pods and deployments. On demoapp, if several apps were running on the same cluster and the database server was always the same, it would be interesting to centralize the configuration.

Edit the demoapp-configmap.yml file and add the requested information about the databsae server on the file. Apply the file and check if the configmap is created:

```bash
kubectl get configmaps
```

Edit the demoapp-deployment.yml and remove the environment variable DATABASE_SERVER and its value. Add the following content under the container configuration (right below the image configuration):

```yaml
        envFrom:
        - configMapRef:
            name: <NAME OF THE CREATED SCONFIGMAP>
```

Apply the configuration again and check if demoapp application still works, displaying the information from the MySQL database. If not, check the deployment again or ask your instructor for help.

## Creating a Secret to store password

Secrets are useful tools to hide sensitive information on Kbernetes. In the demoapp, the DB password is stored as an environment variable, and it can be moved to a Secret.

In order to store a secret, you need to encode the DB password using a base64 algorithm. To do so, use the following command:

```bash
printf '<PUT HERE THE DATABSAE PASSWORD>' | base64
```

Copy the command output as the encoded password. Now, edit the demoapp-secret.yml file, adding the encoded password where indicated. Apply the file and, with "kubectl get secrets", if it's being listed.

Edit the demoapp-deployment file and add the following command right below the name of the configMapRef previously added (inside the envFrom object):

```yaml
        - secretRef:
            name: <NAME OF SECRET CONFIGURED>
```

It should look like this:

```yaml
        envFrom:
        - configMapRef:
            name: <NAME OF THE CREATED SCONFIGMAP>
        - secretRef:
            name: <NAME OF SECRET CONFIGURED>

```

Save and apply the file. test if the demoapp is still working correctly.

## Using ConfigMaps to mount files within the pod

Configmaps can be used to store any kind of information. On this task, an html file will be configured as a configmap injected on the Pods as a volume.

Create an HTML file named index.html on the Linux VM with any information you want. Example below:

```html
<!DOCTYPE html>
<html>
<head>
    <title>HTML file on a great training!</title>
</head>
 
<body>
    <h1 style="color:Blue"> 
        Great training and great lab!  
    </h1>
Will definitely recommend.
</body>
</html>
```

Create a configmap from the file created:

```bash
kubectl create configmap demoapp-newindex-configmap --from-file ./index.html
```

Check the configmap contents in YAML format:

```bash
kubectl get configmap demoapp-newindex-configmap -o yaml
```

Add the following content right at the end of the demoapp-deployment.yml file:

```yaml
        volumeMounts:
        - mountPath: /var/www/html/index.html
          name: demoapp-index
          subPath: index.html
      volumes:
      - name: demoapp-index
        configMap:
          name: demoapp-newindex-configmap
```

* volumeMounts should be inside the "container:" object
* volumes should be inside spec: (PodSpec).

Apply the changed file and check if demoapp application is working correctly. Then, try to access http://\<DEMOAPP_FQDN\>/ (with no demoapp.php on it) and check if your HTML file works.
