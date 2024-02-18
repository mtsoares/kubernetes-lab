# Lab 01

On this lab, we will be installing a simple single-node Kubenetes cluster using K3s distribution. For more details about K3s, check the official documentation: https://k3s.io/ (not mandatory for the lab).

Information needed from your instructor:
* IP and DNS hostname for your lab instance
* SSH private key allowing SSH connection to the lab instance

Follow the instructions below for the installation and configuration.

## Installation

On your SSH client logged into the Linux lab instance, execute the following command to install K3s:

```bash
curl -sfL https://get.k3s.io | sh -
```

After the installation is complete, the K3s cluster configuration file for access is located under the /etc folder on the linux VM. The following commands will create a .kube folder under the actual user directory, copy over the config file and set appropriate permissions.

```bash
mkdir ~/.kube
sudo cp /etc/rancher/k3s/k3s.yaml ~/.kube/config
sudo chown ubuntu ~/.kube/config
```



## Usage

```python
import foobar

# returns 'words'
foobar.pluralize('word')

# returns 'geese'
foobar.pluralize('goose')

# returns 'phenomenon'
foobar.singularize('phenomena')
```

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.
