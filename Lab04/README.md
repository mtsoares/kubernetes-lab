# Lab 04

On this lab, we will work with data persistence on the Wordpress Pod.

## Create a Persistent Volume Claim

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

## Add the PVC to the actual Wordpress Pod

