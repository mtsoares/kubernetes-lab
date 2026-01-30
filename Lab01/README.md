# Lab 01

In this lab, we will be installing a simple single-node Kubenetes cluster using K3s distribution. For more details about K3s, check the official documentation: https://k3s.io/ (not mandatory for the lab).

You need this information from your instructor:
* An IP and an FQDN for your lab instance.
* an SSH private key allowing SSH connection to the lab instance.
* A Username to access the instance. This lab guide assumes the username is "ubuntu".
* A Database server name and an admin password. 

This manual uses "nano" to edit files, but feel free to use any other text editor of choice.

Follow the instructions below for the installation and configuration.

## Preparation

Before installing, a specific non-default configuration is needed to avoid certificate issues on future labs.

Edit the following file (as root): 

```bash
sudo mkdir -p /etc/rancher/k3s/
sudo nano /etc/rancher/k3s/config.yaml 
```

Add the information below on it, replacing \<FQDN\> by the lab VM FQDN provided by the instructor:

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

On your SSH client logged into the Linux lab instance, execute the following command to install K3s and all kube* tools to manage it (kubeadm, kubectl):

```bash
curl -sfL https://get.k3s.io | INSTALL_K3S_VERSION=v1.27.9+k3s1 sh -
```

*NOTE* The version is specified so that all the Helm charts we install work correctly. Usually, there is no need to specify a k3s version in order to use the latest one.

*WARNING* The above command should not be executed in production. Before performing it on production environments, read the documentation first.

Once installation is complete, the K3s cluster configuration file to access is located under the /etc folder on the linux VM. The following commands will create a .kube folder under the actual user directory. Copy over the config file and set appropriate permissions.

```bash
mkdir ~/.kube
sudo cp /etc/rancher/k3s/k3s.yaml ~/.kube/config
sudo chown ubuntu ~/.kube/config
```

Now, the correct environment variable must be set, so that kubectl knows which config file to use:

```bash
export KUBECONFIG=~/.kube/config
```

Test to see if it's possible to check the cluster information:

```bash
kubectl get nodes
```

The next command will persist the KUBECONFIG environment variable. This means that the next time the "ubuntu" user logs in, kubectl will still work:

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

Using a txt file on your own computer, answer the following questions (if you are not sure about any of them, ask your instructor):

* How many namespaces are there in the cluster?
* What namespace has more pods on it?
* Is the cluster using traefik or nginx as ingress controller?
* How many nodes are there in the cluster?
* What are the node names? 

## Challenge

Using the information collected so far, try to configure your personal computer to access the cluster you have just installed using kubectl. If you need any help, ask your instructor. There are some references that may help you in the link below:

* https://kubernetes.io/docs/tasks/tools/
