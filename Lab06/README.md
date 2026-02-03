# Lab 06

In this lab, we will install a Prometheus and Grafana based monitoring solution on the cluster, and then externally access them.

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

Look for a service called "prometheus-grafana", and check what's the NodePort exposed for it (should be 3XXXX). Open a browser and access http://\<VM_FQDN_OR_IP\>:\<NODEPORT\>. Credentials are admin/prom-operator.

Go to the dashboard called "Kubernetes / Compute Resources / Cluster" and observe the data that is displayed. As the presented dashboards are not correctly configured, some data may not be displayed. Try to correct some of the widgets if you know Grafana.

After this is tested, delete the monitoring stack to save lab resources:

```bash
helm uninstall prometheus
```

## Installing Rancher Manager

Rancher Manager community is an opensource tool that allows a graphical UI for managing multiple clusters, including the one where the manager is installed.

Install cert-manager so Rancher can generate certificates:

```bash
kubectl apply -f https://github.com/cert-manager/cert-manager/releases/download/v1.14.2/cert-manager.crds.yaml
helm repo add jetstack https://charts.jetstack.io
helm repo update
helm install cert-manager jetstack/cert-manager --namespace cert-manager --create-namespace
```

Create a namespace for the Rancher Manager:

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

Wait for all the rancher pods to be up checking with "kubectl get pods". This may take around 20 minutes. All pods should be on "Successful" or "Running" state.

In a web browser, connect to https://\<LAB_VM_FQDN\> and provide the following bootstrap password: admin123 (provided on the install command). Create a username and password and login, then check the "local" cluster:

* Check if all namespaces are available for browsing.
* Check if all Pods and Deployments created are visible.
* Check if all Services and Ingresses are available.
* Try to manage the "local" cluster using the kubeconfig file offered by the Rancher manager.
