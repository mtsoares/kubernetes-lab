# Lab 04

En este lab, trabajaremos con persistencia de datos en el pod de Wordpress que hemos creado en el Lab 02.

## Crear una Claim de Volumen

Asegúrate de usar el namespace wordpress-apps:

```bash
kubectl config set-context --current --namespace=wordpress-apps
kubectl config get-contexts
```

Obtén las Storage Classes en el cluster con:

```bash
kubectl get storageclasses
```

Escribe en el notepad el nombre de la clase disponible, ya que la usarás más adelante.

Dirígete a la carpeta del Lab04:

```bash
cd ~/kubernetes-lab/Lab04
```

Edita el archivo wordpress-pvc.yml, cambiando los valores donde sea necesario. Aplica el archivo y revisa que el PVC se haya creado, con:

```bash
kubectl get pvc
```

El estado del PVC deberá ser PENDIENTE (esto cambiará una vez que el pod esté asociado a él). Apunta el nombre del PVC en tu notepad, ya que lo usarás en la siguiente tarea.

## Añadir el PVC al pod de Wordpress actual

Cambiarás el mismo archivo de configuración que usaste en el Lab02:

```bash
nano ~/kubernetes-lab/Lab02/wordpress-pod.yml
```

Añade lo siguiente al "spec" del pod:

```yaml
  volumes:
    - name: <PUT_HERE_A_NAME_FOR_THE_VOLUME>
      persistentVolumeClaim:
        claimName: <PUT_HERE_THE_PVC_NAME>
```

Añade lo siguiente al "container" del pod:

```yaml
    volumeMounts:
    - mountPath: /var/www/html
      name:  <VOLUME_NAME_JUST_CREATED>
```

Guarda el archivo del pod.

Elimina el pod de Wordpress, ejecutando:

```bash
kubectl delete pod \<WORDPRESS_POD_NAME\>
```

Aplica el archivo que se acaba de crear con el montaje del volumen, para que se cree un nuevo pod:

```bash
kubectl apply -f ~/kubernetes-lab/Lab02/wordpress-pod.yml
```

En un navegador, prueba si el sitio Wordpress está funcionando, usando el http://\<STUDENT_WORDPRESS_URL\>. Realiza las siguientes acciones:

* Inicia sesión en Wordpress y crea una nueva publicación, subiendo un archivo de imagen a ella. Revisa que el texto se pueda leer y que la imagen se esté mostrando de forma correcta.
* Elimina el pod de Wordpress. Luego, vuelve a crearlo.
* Revisa si la página que acabas de crear sigue mostrando de forma correcta la imagen que subiste.

Si necesitas ayuda, pídele a tu instructor/a.
