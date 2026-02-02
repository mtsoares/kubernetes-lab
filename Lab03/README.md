# Lab 03

In this lab, we will provide external access for both the example app and the WordPress app via services and ingress.

## Preparation

For this lab, ask your instructor for the Wordpress FQDN you should use.

## Creating a NodePort service for the nginx sample pod.

The Nginx sample pod that we created in the previous exercise is still inaccessible from anywhere. We will be creating a NodePort service to expose it over a TCP port on the 30000-32000 range at the Kubernetes nodes.

Change the namespace back to "default" and make sure the nginx pod created on Lab 02 is there and working:

```bash
kubectl config set-context --current --namespace=default
kubectl get pods
```

Access the folder for Lab03 and edit the nginx-service.yml file:

```bash
cd ~/kubernetes-lab/Lab03/
nano nginx-service.yml
```

Change the values requested on the file so it reflects what was configured in the nginx pod. Then, apply the file. If you need any other information, ask your instructor.

Check that the service has started and is listed with the name provided:

```bash
kubectl get services
```

Under the PORT column of the service you just created, you will notice that a node port was randomly selected (it will appear as 80:3XXXX/TCP, where 3XXXX is the TCP port exposed on the Kubernetes node). Write down the configured TCP port on your notepad.

Now, from a web browser, try to access the nginx pod using http://\<YOUR VM FQDN OR IP\>:3XXXX (replace 3XXXX by the port listed on the previous command). A "Welcome to NGINX" web page should appear.

*CHALLENGE*: using the documentation on https://kubernetes.io/docs/reference/kubernetes-api/service-resources/service-v1/, try to make the nodePort FIXED and not RANDOM.

No other actions will be taken with the nginx pod, but if you want to try other additional configurations, ask your instructor for examples or try it out yourself.

## Exposing the WordPress pod so that it can be accessed over the internet using the default HTTP port and Ingress

Change to the wordpress-apps namespace using:

```bash
kubectl config set-context --current --namespace=wordpress-apps
```

On the Lab03 directory, edit the wordpress-service.yml file and change the appropriate information:
* Service type should be ClusterIP. Ths will allow the service to be used by the ingress that will be created next.
* Make sure the Pod Selector is configured with the same label that the wordpress pod is using.
* You can check the Pod Selector by checking the applied yml file for the pod or using: kubectl describe pod \<PODNAME\>.

Apply the file and check if the service is up:

```bash
kubectl get services
```

If you need any help, ask your instructor.

On the Lab03 directory, edit the wordpress-ingress.yml file and change the appropriate information:
* The Wordpress FQDN provided for this student (each student have a unique FQDN).
* The name of the service you created before.
* The Port for the service you created before.

Apply the wordpress-ingress.yml file and check if the ingress was configured correctly:

```bash
kubectl get ingress
```

Open a browser tab and try to access http://\<WORDPRESS_FQDN\>. Complete the Wordpress setup (writing down on notepad the username and password configred) and test the login to the http://\<WORDPRESS_FQDN\>/wp-admin URL.
