# Lab 05

En este lab, veremos cómo administrar una implementación en Kubernetes, creando y escalando una app llamada demoapp.

## Preparación

Pide la siguiente información a tu instructor o instructora:

* El FQDN de la demoapp para configurar la Entrada
* El URL de la imagen de la demoapp
* Las credenciales de login en el registro y servidor de la demoapp

La demoapp usa las siguientes variables de entorno para su configuración:

* DATABASE_SERVER: el nombre del servidor de base de datos MySQL (que ya pediste para el Lab 02)
* DATABASE_USERNAME: demoappuser
* DATABASE_PASSWORD: securepassword
* DATABASE_NAME: demoappdb

Las cuatro variables de entorno deben configurarse en la implementación de este Lab.

## Crear un namespace 

Crea un namespace para la nueva app and cambia a él:

```bash
kubectl create namespace demoapp
kubectl config set-context --current --namespace=demoapp
```

Dado que la imagen demoapp está alojada en un registro de contenedores que no es Docker Hub, y que está protegida por una autenticación con contraseña, debes decirle explícitamente las credenciales a cualquier pod para el login. Para ello, usa el siguiente comando para crear un secreto de registro de Docker:

```bash
kubectl create secret docker-registry \<PROVIDE A NAME FOR THE SECRET\> --docker-server=\<DOCKER_REGISTRY_SERVER\> --docker-username=\<DOCKER_REGISTRY_USERNAME\> --docker-password=\<DOCKER_REGISTRY_PASSWORD\>
```

Revisa que el secreto se haya creado con "kubectl get secrets" y apúntate su nombre.

Cambia a la carpeta del Lab05 folder y edita el archivo demoapp-deployment.yml:

```bash
cd ~/kubernetes-lab/Lab05
nano demoapp-depoyment.yml
```

Configúralo añadiendo la información requerida en el archivo. Luego, aplica el archivo y revisa que la implementación y los pods hayan iniciado de manera correcta:

```bash
kubectl get deployments
kubectl get pods
```

## Habilitar el acceso externo a la demoapp

Crea un servicio de ClusterIP para la demoapp editando el archivo demoapp-service.yml. Luego, aplica el archivo de servicio.

Crea una Entrada para la demoapp editando el archivo demoapp-ingress.yml. Luego, aplica el archivo de entrada.

Prueba la aplicación abriendo un navegador e ingresando en http://\<DEMOAPP_FQDN\>/demoapp.php. Esta aplicación muestra información que será útil para la resolución de problemas y para la visibilidad:

* Las variables de entorno ofrecidas.
* El nombre del pod que está ejecutando el contenedor.
* La lista de estudiantes agregada a la base de datos MySQL conectada.

## Ampliar la implementación

Edita el archivo demoapp-deployment.yml y cambia la cantidad de réplicas de 1 a 3. Aplica el archivo y:

* Revisa que el número de réplicas haya cambiado, usando "kubectl get deployments"
* Actualiza el navegador y revisa el campo del Pod. Debería cambiar para reflejar desde qué pod se está ejecutando la aplicación.
* Revisa los nombres de los pod haciendo un "kubectl get pods" y revisa que los nombres de pods visibles en la aplicación sean los mismos.

## Crear un configMap para configuraciones comunes

Los ConfigMaps son útiles para reutilizar la misma configuración en distintos pods e implementaciones. Si diversas apps se estuvieran ejecutando en el mismo cluster y el servidor de base de datos fuera siempre el mismo, sería interesante centralizar la configuración.

Edita el archivo demoapp-configmap.yml y añade la información requerida sobre el servidor de base de datos en este archivo. Aplica el archivo y revisa que el configmap se haya creado:

```bash
kubectl get configmaps
```

Edita el archivo demoapp-deployment.yml y elimina la variable de entorno DATABASE_SERVER y su valor. Añade la siguiente configuración a la configuración del contenedor (justo debajo de la configuración de la imagen):

```yaml
        envFrom:
        - configMapRef:
            name: <NAME OF THE CREATED SCONFIGMAP>
```

Aplica la configuración una vez más y revisa si la aplicación demoapp sigue funcionando, mostrando la información de la base de datos de MySQL. Si esto no fuera así, revisa la implementación de nuevo o pide ayuda a tu intructor o instructora.

## Crear un Secreto para almacenar la contraseña

Los Secretos son herramientas útiles para esconder información sensible en Kbernetes. En la demoapp, la contraseña de la base de datos se almacena en una variable de entorno, y puede moverse a un Secreto.

Para almacenar un secreto, debes codificar la contraseña de la base de datos usando el algoritmo base64. Para ello, usa el siguiente comando:

```bash
printf '<PUT HERE THE DATABSAE PASSWORD>' | base64
```

Copia el output del comando como la contraseña codificada. Luego, edita el archivo demoapp-secret.yml, añadiendo la contraseña codificado donde se indique. Aplica el archivo y revisa, con "kubectl get secrets", si está siendo listada.

Edita el archivo demoapp-deployment y añade lo siguiente justo debajo del nombre del configMapRef que añadiste antes (dentro del objeto envFrom):

```yaml
        - secretRef:
            name: <NAME OF SECRET CONFIGURED>
```

Debería lucir así:

```yaml
        envFrom:
        - configMapRef:
            name: <NAME OF THE CREATED SCONFIGMAP>
        - secretRef:
            name: <NAME OF SECRET CONFIGURED>

```

Guarda y aplica el archivo. Prueba si la demo app sigue funcionando de manera correcta.

## Usar ConfigMaps para montar archivos dentro del pod

El Configmaps puede usarse para almacenar cualquier tipo de información. En esta tarea, un archivo html será configurado como un configmap y luego inyectado en los Pods como un volumen.

En la VM de Linux VM, crea un archivo HTML file llamado index.html con cualquier información que quieras. Por ejemplo:

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

Crea un a configmap del archivo que creaste:

```bash
kubectl create configmap demoapp-newindex-configmap --from-file ./index.html
```

Revista el contenido del configmap en formato YAML

```bash
kubectl get configmap demoapp-newindex-configmap -o yaml
```

Añade lo siguiente justo al final del archivo demoapp-deployment.yml:

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

* Los volumeMounts deben estar dentro del objeto "container:".
* Los volúmenes deben estar dentro de: (PodSpec).

Aplica el archivo modificado y revisa si la aplicación demoapp está funcionando de manera correcta. Luego, intenta ingresar en http://\<DEMOAPP_FQDN\>/ (sin demoapp.php en él) y revisa si tu archivo HTML está funcionando.

