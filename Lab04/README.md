# Lab 04

On this lab, we will work with data persistence on the Wordpress Pod.

## Create a Persisten Volume Claim

Get the Storage Classes present on the cluster with:

```bash
kubectl get storageclasses
```

Write on the notepad the name of the class available, as it will be used later.

Move to the Lab04 folder:

```bash
cd ~/kubernetes-lab/Lab04
```

Edit the wordpress-pvc.yml file, changing the values where requested. Apply the file and check if the PVC have been created with:

```bash
kubectl get pvc
```


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
