# Lab 06

On this lab, a Prometheus and Grafana based monitoring solution will be installed on the cluster and externally accessed.

## Preparation

Create a namespace for the monitoring infrastructure:

```bash
kubectl create namespace monitoring
kubectl config set-context --current --namespace=monitoring
```

Access the Lab06 folder containg configuration files for the monitoring tools. There is no need to change any configuration file:

```bash
cd ~/kubernetes-lab/Lab06/
```

Install the helm tool on the Linux VM:

```bash
sudo snap install helm --classic
```

Add the prometheus default repository to the local helm:

```bash
helm repo add prometheus-community https://prometheus-community.github.io/helm-charts
```

Install the monitoring stack:

```bash
helm upgrade --install prometheus prometheus-community/kube-prometheus-stack --version 39.13.3 --values kube-prometheus-stack-values.yaml
```

Check if all pods for monitoring are up:

```bash
kubectl get pods
```

Check the NodePort service created for grafana:

```bash
kubectl get svc
```

Look for a service called "prometheus-grafana", and check what is the NodePort exposed for it (should be 3XXXX). Open a browser and access http://\<VM_FQDN_OR_IP\>:\<NODEPORT\>. Credentials are admin/prom-operator.

Navigate to the dashboard named "Kubernetes / Compute Resources / Cluster" and observe the data being displayed.

## Installing Rancher Manager

Rancher manager community is an opensource tool that allows a graphical UI for managing multiple clusters., including the one where the manager is installed.

Install cert-manager so Rancher can generate certificates:

```bash
kubectl apply -f https://github.com/cert-manager/cert-manager/releases/download/v1.14.2/cert-manager.crds.yaml
helm repo add jetstack https://charts.jetstack.io
helm repo update
helm install cert-manager jetstack/cert-manager --namespace cert-manager --create-namespace
```

Create a namespace for the Rancher manager:

```bash
kubectl create namespace cattle-system
kubectl config set-context --current --namespace=cattle-system
```

Add the helm repository for Rancher:

```bash
helm repo add rancher-latest https://releases.rancher.com/server-charts/latest
helm repo update
```

Execute the following command to perform the installation:

```bash
helm install rancher rancher-latest/rancher --namespace cattle-system --set hostname=<LAB_VM_FQDN> --set bootstrapPassword=admin123
```

Wait for all rancher pods to be up checking with "kubectl get pods". This may take around 20 minutes. All pods should be on Successful or Running state.

On a browser, connect to https://\<LAB_VM_FQDN\> and provide the following bootstrap password: admin123 (provided on the install command). Create a username and password and login, then check the "local" cluster:

* Check if all namespaces are available for browsing
* Check if all Pods and Deployments created are visible
* Check if all Services and Ingresses are available
