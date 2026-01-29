# Lab 02

En este lab, se crearán un Pod de muestra y un Pod de Wordpress con acceso a una base de datos MySQL, lo que permitirá que Worpress funcione correctamente.

## Crear un pod simple

Crea un archivo llamado nginx-pod.yml usando un editor:

```bash
nano nginx-pod.yml
```

Añade el siguiente comando al archivo, usando la última versión del pod oficial nginx en el Hub de Docker:

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

Aplica el archivo:

```bash
kubectl apply -f nginx-pod.yml
```

Revisa que el pod con el nombre provisto haya empezado. Luego, revisa los detalles sobre este pod:

```bash
kubectl get pods
kubectl get pods -o wide
```

Revisa los logs para el pod:

```bash
kubectl logs PODNAME
```

Revisa la información del the pod:

```bash
kubectl describe pod PODNAME
```

Añade la siguiente sección de "metadata" y aplica el archivo usando el mismo comando anterior:

```yaml
  labels:
    app: nginx-pod
```

Revisa los logs para cualquiera de los pods que tengan la etiqueta:

```bash
kubectl logs --selector app=nginx-pod
```

Deja el of funcionando para un lab futuro.

## Preparación para la implementación en Wordpress

Usando la documentación oficial de Docker para el contenedor de Wordpress en https://hub.docker.com/_/wordpress, encuentra y escribe las variables de entorno necesarias para una implementación exitosa en Worpress:

* Variable para el servidor de base de datos hostname/IP
* Variable para el nombre de usuario de la base de datos
* Variable para la contraseña de usuario de la base de datos
* Variable para el nombre de la base de datos en el servidor de base de datos

Pide la siguiente información a tu instructor o instructora:

* La IP/FQDN del servidor de base de datos
* El nombre que debes usar para la base de datos 
* El nombre de usuario y contraseña para acceder a la base de datos

Esta información se usará en los próximos laboratorios.

Crea un namespace para la implementación en Wordpress y configúralo como el namespace actual:

```bash
kubectl create namespace wordpress-apps
kubectl config set-context --current --namespace=wordpress-apps
```

## Crear un pod de Wordpress

Abre la carpeta del Lab02, que contiene algunos archivos de ayuda:

```bash
cd ~/kubernetes-lab/Lab02
```

Edita el archivo wordpress-pod.yml, añadiendo la información solicitada en el archivo. Luego, aplica el archivo creado y revisa que el pod haya empezado correctamente. Debes:

* Obtener todos los pods en el namespace wordpress-apps actual y revisar sus estados
* Revisar los logs para el pod que creaste.
(Para esto, usa los comandos que ya usaste en ejercicios anteriores)

Si el pod no empieza correctamente o presenta algún error, revisa la configuración y, si el problema persiste, pide ayuda a tu instructor o instructora.
