# Lab 02

In this lab, we will create two pods: a sample pod and a WordPress pod with access to a MySQL database, enabling WordPress to run successfully.

## Create a simple pod

Using a text editor, create a file called nginx-pod.yml:

```bash
nano nginx-pod.yml
```

Add the following content to the file, using the latest version of the official nginx pod on the Docker Hub:

```yaml
apiVersion: v1
kind: Pod
metadata:
  name: ## Replace here by a suitable pod name ##
spec:
  containers:
  - name: ## Replace here by a suitable container name ##
    image: ## Replace here by a suitable image for nginx ##
```

Apply the file:

```bash
kubectl apply -f nginx-pod.yml
```

Check whether the pod with the provided name has started, and check its details:

```bash
kubectl get pods
kubectl get pods -o wide
```

Check the pod's logs:

```bash
kubectl logs PODNAME
```

Check the pod information:

```bash
kubectl describe pod PODNAME
```

In the Pod's "metadata" section,  add the following command. Then, apply the file using the same command you used before:

```yaml
  labels:
    app: nginx-pod
```

Check the logs for any pods that have this label:

```bash
kubectl logs --selector app=nginx-pod
```

Leave the pod running for a later lab.

## Preparation for the Wordpress implementation

Using the official documentation for the Wordpress container by Docker on https://hub.docker.com/_/wordpress, find and write down the environment variables needed for a successful Wordpress implementation:

* Variable for the database server hostname/IP.
* Variable for the database username.
* Variable for the database user password.
* Variable for the database name on the database server.

Ask your instructor for the following information:

* An IP/FQDN for the Database server.
* The name of the Database you should use.
* A username and a password to access the Database.

This information will be used in future labs.

Create a namespace for the wordpress implementation and set it as the current namespace:

```bash
kubectl create namespace wordpress-apps
kubectl config set-context --current --namespace=wordpress-apps
```

## Creating a wordpress pod

Access the folder Lab02, which contains some helper files:

```bash
cd ~/kubernetes-lab/Lab02
```

Edit the wordpress-pod.yml file adding the required information to it. Once you have finished, apply the created file and check if the pod has started correctly by:

* Getting all the pods on the current namespace wordpress-apps and checking their status.
* Checking the logs for the created pod.
(Use the same commands you already used in previous exercises.)

If the pod is not starting or is having an error, review the configuration. If the problem persists, ask your the instructor for help.
