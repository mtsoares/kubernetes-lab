# Lab 03

En este lab, usaremos services y ingresses para darle acceso externo a la app de prueba y también para la app de Wordpress.

## Preparación

Para este lab, pide el FQDN de Wordpress que deberás usar a tu instructor/a.

## Crear un service de NodePort para el pod de ejemplo nginx

El pod de prueba que creaste en el ejercicio anterior aún no puede accederse desde ninguna parte. Crearemos un service de tipo NodePort para exponerlo a través de un puerto TCP en el rango 30000-32000 en los nodos de Kubernetes.

Vuelve a cambiar el namespace al "default" y asegúrate de que el pod nginx que creaste en el Lab 02 siga allí y que esté funcionando:

```bash
kubectl config set-context --current --namespace=default
kubectl get pods
```

Dirígete a la carpeta del Lab03 y edita el archivo nginx-service.yml:

```bash
cd ~/kubernetes-lab/Lab03/
nano nginx-service.yml
```

Cambia los valores solicitados en el archivo para que reflejen lo que se haya configurado en el pod nginx y aplica el archivo. Si necesitas más información, pide ayuda a tu instructor/a.

Revisa que el service se haya iniciado y que esté listado bajo el nombre provisto:

```bash
kubectl get services
```

En la columna PORT del service creado, notarás que un puerto en los nodos se ha seleccionado de manera aleatoria (figurará como 80:3XXXX/TCP, donde 3XXXX es el puerto TCP expuesto en el nodo de Kubernetes). En tu notepad, anota el puerto TCP configurado.

Ahora, desde un navegador, intenta acceder al pod nginx usando http://\<YOUR VM FQDN OR IP\>:3XXXX (reemplaza 3XXXX por el puerto listado en el comando anterior). Debería abrirse una página web de "Welcome to NGINX".

*DESAFÍO*: usando la documentación en https://kubernetes.io/docs/reference/kubernetes-api/service-resources/service-v1/, intenta hacer que el NodePort sea FIJO y no ALEATORIO.

No se realizarán otras acciones con el pod nginx, pero si quieras probar configuraciones adicionales, pide ejemplos a tu instructor/a, o bien pruébalo tú mismo/a.

## Exponer el pod de Wordpress para que se pueda acceder en internet usando un puerto HTTP predeterminado y un Ingress

Cambia al namespace wordpress-apps usando:

```bash
kubectl config set-context --current --namespace=wordpress-apps
```

En el directorio del Lab03, edita el archivo wordpress-service.yml y cambia la información apropiada:
* El tipo de service debería ser ClusterIP. Esto permitirá que el Ingress (que crearemos a continuación) utilice el service.
* Asegúrate de que el selector de pods esté configurado con la misma etiqueta que esté usando el pod de Wordpress.
* Puedes revisar el selector de pods consultando el archivo yml aplicado para el pod, o bien usando kubectl describe pod \<PODNAME\>

Aplica el archivo y comprueba que el service esté activo:

```bash
kubectl get services
```

Si necesitas ayuda, pídele a tu instructor/a.

En el directorio del Lab03, edita el archivo wordpress-ingress.yml y cambia la información apropiada:
* FQDN de Wordpress para ese estudiante (cada estudiante tiene un FQDN único).
* Nombre del servicio creado antes.
* Puerto del service creado antes.

Aplica el archivo wordpress-ingress.yml y revisa que el Ingress se haya configurado de manera correcta:

```bash
kubectl get ingress
```

Abre una pestaña en un navegador e intenta ingresar en http://\<WORDPRESS_FQDN\>. Completa la configuración de Wordpress (escribiendo en notepad el nombre de usuario y contraseña configurados) e intenta iniciar sesión en la URL http://\<WORDPRESS_FQDN\>/wp-admin.

