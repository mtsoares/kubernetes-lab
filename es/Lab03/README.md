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

*DESAFÍO*: usando la documentación en https://kubernetes.io/docs/reference/kubernetes-api/service-resources/service-v1/, intenta hacer que el NodePort sea FIJO y no ALEATORIO.

No se realizarán otras acciones con el pod nginx, pero si quieras probar otras configuraciones adicionales, pide ejemplos a tu instructor/a, o bien o pruébalo tú mismo/a.

## Exponer el pod de Wordpress para que se pueda acceder en internet usando un puerto HTPP predeterminado y Entrada

Cambia al namespace wordpress-apps usando:

```bash
kubectl config set-context --current --namespace=wordpress-apps
```

En el directorio del Lab03, edita el archivo wordpress-service.yml y cambia la información apropiada:
* Service type should be ClusterIP. Ths will allow the service to be used by the ingress that will be created next.
* Make sure the Pod Selector is configured with the label the wordpress pod is using.
* You can check the Pod Selector by checking the applied yml file for the pod or using kubectl describe pod \<PODNAME\>

Aplica el archivo y comprueba que el servicio esté activo:

```bash
kubectl get services
```

Si necesitas ayuda, pídele a tu instructor o instructora.

En el directorio del Lab03, edita el archivo wordpress-ingress.yml y cambia la información apropiada:
* FQDN de Wordpress para ese estudiante (cada estudiante tiene un FQDN único)
* Nombre del servicio creado anteriormente
* Puerto del servicio creado anteriormente

Aplica el archivo wordpress-ingress.yml y revisa si la entrada se configuró correctamente:

```bash
kubectl get ingress
```

Abre una pestaña en un navegador e intenta acceder a http://\<WORDPRESS_FQDN\>. Completa el setup de Wordpress (escribiendo en notepad el nombre de usuario y contraseña configurada) y prueba el login a http://\<WORDPRESS_FQDN\>/wp-admin URL.

