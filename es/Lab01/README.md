# Lab 01

En este lab, instalaremos un cluster sencillo de un solo nodo de Kubernetes, usando la distribución K3s. Para más información sobre K3s, consulta la documentación oficial: https://k3s.io/ (no es obligatorio para este lab).

Necesitarás pedirle la siguiente información a tu instructor/a:
* Una IP y un FQDN para tu instancia de lab.
* Una clave privada de SSH que permita la conexión SSH a tu instancia de lab.
* Un nombre de usuario para iniciar sesión en la instancia. Este lab asume que el nombre de usuario es "ubuntu".
* Un nombre de servidor de base de datos y una contraseña de administrador. 

Este manual usa "nano" para editar archivos, pero puedes usar el editor de texto que prefieras.

Sigue las instrucciones a continuación para la instalación y configuración.

## Preparación

Antes de la instación, necesitarás una configuración específica (no predeterminada) para evitar problemas con los certificados en labs futuros.

Edita el siguiente archivo (como root): 

```bash
sudo mkdir -p /etc/rancher/k3s/
sudo nano /etc/rancher/k3s/config.yaml 
```

Añade la siguiente información al archivo, reemplazando \<FQDN\> por el FQDN de la VM del lab (recuerda: este número te lo dará el instructor/a):

```yaml
tls-san:
  - "<FQDN>"
```

Guarda el archivo (CTRL+O) y luego ciérralo (CTRL+X).

Clona el repositorio del lab en el directorio de inicio de «ubuntu»:

```bash
cd ~
git clone https://github.com/mtsoares/kubernetes-lab.git
```

## Instalación

Con tu cliente SHH logueado en la instancia del lab de Linux, ejecuta el siguiente comando para instalar el K3s y todas las herramientas de kube* (kubeadm, kubectl) para admnistrarlo:

```bash
curl -sfL https://get.k3s.io | INSTALL_K3S_VERSION=v1.27.9+k3s1 sh -
```

*NOTA:* La versión se especifica para que todos los gráficos Helm que instalamos funcionen de manera correcta. Por lo general, no hace falta especificar una versión de k3s para usar la más reciente.

*ADVERTENCIA:* El comando anterior no se debe ejecutar en producción. Antes de realizarlo en entornos de producción, lee la documentación.

Una vez que se haya completado la instalación, el archivo de configuración del cluster K3s para acceder se ubica en la carpeta /etc en la VM de Linux. El siguiente comando creará una carpeta .kube dentro del directorio del usuario actual. Copia el archivo config y establece los permisos apropiados.

```bash
mkdir ~/.kube
sudo cp /etc/rancher/k3s/k3s.yaml ~/.kube/config
sudo chown ubuntu ~/.kube/config
```

En este momento, necesitarás establecer la variable de entorno correcta para que el kubectl sepa qué archivo config usar:

```bash
export KUBECONFIG=~/.kube/config
```

Prueba si es posible verificar la información del cluster:

```bash
kubectl get nodes
```

El siguiente comando perpetuará la variable de entorno KUBECONFIG para que, la próxima vez que el usuario de "ubuntu" inicie sesión, el kubectl siga funcionando:

```bash
echo -e "KUBECONFIG=/home/ubuntu/.kube/config" | sudo tee -a /etc/environment
```

Ejecuta cada uno de los siguientes comandos y analiza el output:

```bash
kubectl cluster-info
kubectl get nodes -o wide
kubectl get namespaces
kubectl get services --all-namespaces
kubectl get pods -o wide --all-namespaces
```

En un archivo txt en tu propia computadora, responde las siguientes preguntas (si tienes dudas sobre alguna de ellas, puedes preguntarle a tu instructor/a):

* ¿Cuántos namespaces hay en el cluster?
* ¿Qué namespace tiene más pods?
* ¿El cluster está usando traefik o nginx como controlador de entrada?
* ¿Cuántos nodos hay en el cluster?
* ¿Cómo se llaman los nodos? 
