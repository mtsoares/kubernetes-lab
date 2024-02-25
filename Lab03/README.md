# Lab 03

On this lab, external access will be provided for the example app and also for the Wordpress app using services and ingresses.

## Preparation

For this lab, ask your instructor for the Wordpress FQDN that will be used.

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

Now, from a web browser, try to access the nginx pod using http://\<YOUR VM FQDN OR IP\>:3XXXX (replace 3XXXX by the port listed on the previous command). a "Welcome do NGINX" web page should appear.

*CHALLENGE*: using the documentation on https://kubernetes.io/docs/reference/kubernetes-api/service-resources/service-v1/, try to make the nodePort FIXED and not RANDOM.

No other actions will be taken with the nginx pod, but if you want to try other additional configurations ask the instructor for examples or try it yourself.

## Exposing the Wordpress pod to be accessed over the internet using a default HTTP port and Ingress

Change to the wordpress-apps namespace using:

```bash
kubectl config set-context --current --namespace=wordpress-apps
```

On the Lab03 lab directory, edit the wordpress-service.yml file and change the appropriate information:
* Service type should be ClusterIP. Ths will allow the service to be used by the ingress that will be created next.
* Make sure the Pod Selector is configured with the label the wordpress pod is using.
* You can check the Pod Selector by checking the applied yml file for the pod or using kubectl describe pod \<PODNAME\>

Apply the file and check if the service is up:

```bash
kubectl get services
```

In case you need assistance, ask your instructor.

On the Lab03 lab directory, edit the wordpress-ingress.yml file and change the appropriate information:
* Provided Wordpress FQDN for this student (each student have a unique FQDN)
* Name of the service created previously
* Port for the service created previously

Apply the wordpress-ingress.yml file and check if the ingress was correctly configured:

```bash
kubectl get ingress
```

Open a browser tab and try to access http://\<WORDPRESS_FQDN\>. Complete the Wordpress setup (writing down on notepad the username and password configred) and test the login to the http://\<WORDPRESS_FQDN\>/wp-admin URL.
