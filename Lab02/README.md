# Lab 02

In this lab, a Pod will be created with access to a MySQL database, enabling Worpress to run successfully.

## Create a simple pod

Create a file called nginx-pod.yml using an editor:

```bash
nano nginx-pdo.yml
```

Add the following to the file:

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

## Preparation

Using the official documentation for the Wordpress container by Docker on https://hub.docker.com/_/wordpress, find and write down the information that is needed for a successful Wordpress implementation.

Use the package manager [pip](https://pip.pypa.io/en/stable/) to install foobar.

```bash
pip install foobar
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
