# Lab 01

On this lab, we will be installing a simple single-node Kubenetes cluster using K3s distribution. For more details about K3s, check the official documentation: https://k3s.io/ (not mandatory for the lab).

Information needed from your instructor:
* IP and FQDN for your lab instance.
* SSH private key allowing SSH connection to the lab instance.
* Username to access the instance. This lab guide assumes the username is "ubuntu".
* Database server name and admin password. 

The manual uses "nano" to edit files, but feel free to use any other text editor of choice.

Follow the instructions below for the installation and configuration.

## Preparation

Before installing, a specific non-default configuration is needed to avoid certificate issues on future labs.

Edit the following file (as root): 

```bash
sudo mkdir -p /etc/rancher/k3s/
sudo nano /etc/rancher/k3s/config.yaml 
```

Add the below information on it, replacing \<FQDN\> by the lab VM FQDN provided by the instructor:

```yaml
tls-san:
  - "<FQDN>"
```

Save the file (CTRL+O) and exit (CTRL+X).

Clone the lab repository on the "ubuntu" home directory:

```bash
cd ~
git clone https://github.com/mtsoares/kubernetes-lab.git
```

## Installation

On your SSH client logged into the Linux lab instance, execute the following command to install K3s and all kube* (kubeadm, kubectl) tools to manage it:

```bash
curl -sfL https://get.k3s.io | INSTALL_K3S_VERSION=v1.27.9+k3s1 sh -
```

*NOTE* The version is specified so all helm charts we install works correctly. Usually ther is no need to specify a k3s version to use the latest one.

*WARNING* The above command should not be executed in production. Read the documentation before performing it on production environments.

After the installation is complete, the K3s cluster configuration file for access is located under the /etc folder on the linux VM. The following commands will create a .kube folder under the actual user directory, copy over the config file and set appropriate permissions.

```bash
mkdir ~/.kube
sudo cp /etc/rancher/k3s/k3s.yaml ~/.kube/config
sudo chown ubuntu ~/.kube/config
```

Now it is needed to set the correct environment variable so kubectl knows what config file to use:

```bash
export KUBECONFIG=~/.kube/config
```

Test if it is possible to check cluster information:

```bash
kubectl get nodes
```

The next command will persist the KUBECONFIG environment variable so next time the "ubuntu" user logs in kubectl will still work:

```bash
echo -e "KUBECONFIG=/home/ubuntu/.kube/config" | sudo tee -a /etc/environment
```

Execute each one of the following commands and analyze the output:

```bash
kubectl cluster-info
kubectl get nodes -o wide
kubectl get namespaces
kubectl get services --all-namespaces
kubectl get pods -o wide --all-namespaces
```

Using a txt file on your own computer answer the following (as the instructor if you are not sure about any question):

* How many namespaces are in the cluster?
* What namespace have more pods on it?
* Is the cluster using traefik or nginx as ingress controller?
* How many nodes are in the cluster?
* What are the node names? 
