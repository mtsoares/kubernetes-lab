# Lab 02

In this lab, a Pod will be created with access to a MySQL database, enabling Worpress to run successfully.

## Create a simple pod

Create a file called nginx-pod.yml using an editor:

```bash
nano nginx-pod.yml
```

Add the following to the file, using the latest version of the official nginx pod on the Docker Hub:

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

Check if the pod with the name provided have started and check details about the pod:

```bash
kubectl get pods
kubectl get pods -o wide
```

Check logs for the pod:

```bash
kubectl logs PODNAME
```

Check the pod information:

```bash
kubectl describe pod PODNAME
```

Add the following to the Pod "metadata" section and apply the file using the same command used previously:

```yaml
  labels:
    app: nginx-pod
```

Check logs for any pods that have that label:

```bash
kubectl logs --selector app=nginx-pod
```

Leave the pod running for a later lab.

## Preparation for the Wordpress implementation

Using the official documentation for the Wordpress container by Docker on https://hub.docker.com/_/wordpress, find and write down the environment variables  that are needed for a successful Wordpress implementation:

* Variable for the database server hostname/IP
* Variable for the database username
* Variable for the database user password
* Variable for the database name on the database server

Ask your instructor for the following information:

* Database server IP/FQDN
* Database name to be used
* Database username and password to access the database

This information will be used on the subsequent labs.

## Creating a wordpress pod

Access Lab02 folder containing some helper files:

```bash
cd ~/kubernetes-lab/Lab02
```


Use the package manager [pip](https://pip.pypa.io/en/stable/) to install foobar.

pip install foobar
```

## Usage

```python
import foobar

# returns 'words'
foobar.pluralize('word')

# returns 'geese'
foobar.pluralize('goose')

# returns 'phenomenon'
foobar.singularize('phenomena')
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.
