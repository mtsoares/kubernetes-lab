# Lab 03

En este lab, usaremos servicios y entradas para darle acceso externo a la app de ejemplo y también para la app de Wordpress.

## Preparación

Para este lab, pide el FQDN de Wordpress que deberás usar a tu instructor/a.

## Crear un servicio de NodePort para el pod de muestra nginx.

El pod de prueba que creaste en el ejercicio anterior aún no puede accederse desde ninguna parte. Crearemos un servicio de NodePort para exponerlo a través de un puerto TCP en el rango 30000-32000 en los nodos de Kubernetes.

Cambia nuevamente el namespace al "default" y asegúrate de que el pod nginx que creaste en el Lab 02 siga allí y esté funcionando:

```bash
kubectl config set-context --current --namespace=default
kubectl get pods
```

Accede a la carpeta del Lab03 y edita el archivo nginx-service.yml:

```bash
cd ~/kubernetes-lab/Lab03/
nano nginx-service.yml
```

Cambia los valores solicitados en el archivo para que reflejen lo que se configuró en el pod nginx pod y aplica el archivo. Si necesitas alguna información, pide ayuda a tu instructor/a.

Revisa que el servicio se haya iniciado y esté listado bajo el nombre provisto:

```bash
kubectl get services
```

En la columna PORT del servicio creado, notarás que un puerto de nodos se ha seleccionado de manera aleatoria (figurará como 80:3XXXX/TCP, donde 3XXXX es el puerto TCP expuesto en el nodo de Kubernetes). En tu notepad, anota el puerto TCP configurado.

Ahora, desde un navegador, intenta acceder al pod nginx pod usando http://\<YOUR VM FQDN OR IP\>:3XXXX (reemplaza 3XXXX por el puerto listado en el comando anterior). Debería aparecer una página web de "Welcome to NGINX".

*DESAFÍO*: usando la documentación en https://kubernetes.io/docs/reference/kubernetes-api/service-resources/service-v1/, intenta hacer que el NodePort sea FIXED y no RANDOM.

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

