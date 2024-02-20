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

