# Lab 03

On this lab, external access will be provided for the example app and also for the Wordpress app using services and ingresses.

## Creating a NodePort service for the nginx sample pod.

The Nginx sample pod created on the previous exercise is still not accessible from anywhere. We will be createting a NodePort service to expose it over a TCP port on the 30000-32000 range at the Kubernetes nodes.

Change the namespace back to the "default" one and make sure the nginx pod created on Lab 02 is there and working:

```bash
kubectl config set-context --current --namespace=default
kubectl get pods
```

Access the Lab03 folder and edit the nginx-service.yml file:

```bash
cd ~/kubernetes-lab/Lab03/
nano nginx-service.yml
```

Change the values requested on the file so it reflects what have been configured on the nginx pod and apply the file. If you need any information, ask the instructor.

Check that the service have been started and listed with the name provided:

```bash
kubectl get services
```

Under the PORT column of the service created, you will notice that a node port was randomly selected (it will appear as 80:3XXXX/TCP, where 3XXXX is the TCP port exposed on the Kubernetes node). Write the configured TCP port on your notepad for notes.

Now, from a web browser, try to access the nginx pod using http://\<YOUR VM URL\>:3XXXX (replace 3XXXX by the port listed on the previous command). a "Welcome do NGINX" web page should appear.

*CHALLENGE*: using the documentation on https://kubernetes.io/docs/reference/kubernetes-api/service-resources/service-v1/, try to make the nodePort FIXED and not RANDOM.

No other actions will be taken with the nginx pod, but if you want to try other additional configurations ask the instructor for examples or try it yourself.

## Exposing the Wordpress pod to be accessed over the internet using a default HTTP port and Ingress



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
