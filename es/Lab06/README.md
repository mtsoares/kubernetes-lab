# Lab 06

En este lab, instalaremos una solución de supervisión basada en Prometheus y Grafana en el cluster, y accederemos a ella de manera externa.

## Preparación

Crea un namespace para la infraestructura de supervisión:

```bash
kubectl create namespace monitoring
kubectl config set-context --current --namespace=monitoring
```

Dirígete a la carpeta del Lab06, que contiene los archivos de configuración para las herramientas de supervisión. No hace falta que cambies ningún archivo de configuración:

```bash
cd ~/kubernetes-lab/Lab06/
```

Instala la herramienta de helm en la VM de Linux:

```bash
sudo snap install helm --classic
```

Añade el repositorio predeterminado de Prometheus al helm local:

```bash
helm repo add prometheus-community https://prometheus-community.github.io/helm-charts
```

Instala el stack de supervisión:

```bash
helm upgrade --install prometheus prometheus-community/kube-prometheus-stack --version 39.13.3 --values kube-prometheus-stack-values.yaml
```

Revisa que todos los pods de supervisión estén encendidos:

```bash
kubectl get pods
```

Revisa el servicio de NodePort creado para Grafana:

```bash
kubectl get svc
```

Busca un servicio llamado "prometheus-grafana", y revisa cuál es el NodePort expuesto (debería ser 3XXXX). Abre un navegador e ingresa en http://\<VM_FQDN_OR_IP\>:\<NODEPORT\>. Las credenciales son admin/prom-operator.

Dirígete al panel llamado "Kubernetes / Compute Resources / Cluster" y observa la información que se muestra. Dado que los paneles presentados no están configurados de manera correcta, es posible que algunos datos no se muestren. Intenta conectar alguno de los widgets, si conoces Grafana.

Luego de probar esto, elimina el stack de supervisión para guardar los recursos del lab:

```bash
helm uninstall prometheus
```

## Instalar un Rancher Manager

La comunidad de Rancher Manager es una herramienta opensource que ofrece una UI gráfica para manejar múltiples clusters, incluyendo el cluster en el que esté instalado el Manager.

Instala el cert-manager para que el Rancher pueda generar certificados:

```bash
kubectl apply -f https://github.com/cert-manager/cert-manager/releases/download/v1.14.2/cert-manager.crds.yaml
helm repo add jetstack https://charts.jetstack.io
helm repo update
helm install cert-manager jetstack/cert-manager --namespace cert-manager --create-namespace
```

Crea un a namespace para el Rancher Manager:

```bash
kubectl create namespace cattle-system
kubectl config set-context --current --namespace=cattle-system
```

Añade el repository de helm para Rancher:

```bash
helm repo add rancher-latest https://releases.rancher.com/server-charts/latest
helm repo update
```

Ejecuta el siguiente comando para realizar la instalación:

```bash
helm install rancher rancher-latest/rancher --namespace cattle-system --set hostname=<LAB_VM_FQDN> --set bootstrapPassword=admin123
```

Espera a que todos los pods de Rancher estén activos, revisándolo con «kubectl get pods». Esto puede tardar unos veinte minutos. Todos los pods deben estar en estado "Exitoso" o "En ejecución".

En un navegador, ingresa en https://\<LAB_VM_FQDN\> y proporciona la siguiente contraseña de bootstrap : admin123 (provista en el comando de instalación). Crea un usuario, una contraseña e inicia sesión. Luego, revisa el cluster "local":

* Revisa que todos los namespaces estén disponibles para la navegación.
* Revisa que todos los Pods e implementaciones creadas sean visibles.
* Revisa que todos los Servicios y Entradas estén disponibles.
* Intenta administrar el cluster "local" usando el archivo de kubeconfig file ofrecido por el Rancher Manager.
