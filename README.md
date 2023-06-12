# Example of decentralized app

Here is a tutorial on how you can create and deploy your own dapp on php coin network.

As an example we will create an app that login users to blockchain with [gateway](https://main1.phpcoin.net/dapps.php?url=PeC85pqFgRxmevonG6diUwT4AfF7YUPSm3/gateway) api and allows them to check balance (public API access).

Also as an example of private access we will allow the user to store its address label on a node.

Address will be stored in the node internal database but other nodes can access it through the dapp API.

This is not an example of full decentralized storage, but it will show basic principles of this concept.

This app when published can run on any node, but storing address labels will be only on the node owner.

Then the app will be used to show a label of address on other nodes.

Full deployed dapp is available on address:

https://main2.phpcoin.net/dapps.php?url=PoApBr2zi84BEw2wtseaA2DtysEVCUnJd7/labeler

## Setup new node

One dapp is deployed on a single node on the network (called owner). 

That node holds the private key of dapp to prove ownership of dapp and with special functions can access the underlying owner node.

Deployed dapp is then distributed over the network to other nodes.

Dapps can be public, private or both.

Public dapp can run on any node, execute common api functions and do not depend on node software. An example of this app is the gateway app.

Private dapp can run on any node but for some functionality need to contact the dapp owner. 

For example when the owner node stores some data then other apps can interact with it through api. 

Example of such an app is [faucet](https://main1.phpcoin.net/dapps.php?url=PeC85pqFgRxmevonG6diUwT4AfF7YUPSm3/faucet) or [verifier](https://main1.phpcoin.net/dapps.php?url=PeC85pqFgRxmevonG6diUwT4AfF7YUPSm3/verifier), because only the owner of the node holds addresses private keys. 

First step is to set up and sync the node.

For setup node use standard [install script](https://github.com/phpcoinn/node/wiki/Node-installation) or install via [docker](https://hub.docker.com/u/phpcoin)

Important is that the node is fully synced in order to have a full list of live peers. 

That peers will then receive any updates on dapp.

## Setup dapp address

For running dapp owner on node it must have configured and assigned address. 

By that address all nodes must access node dapp. 

So it is important that the address is verified, i.e. it must record the public key on the blockchain.

Create a new PHPCoin address which will be assigned to dapp and verify it.

For this tutorial we will user node main2 and setup dapp on address: **PoApBr2zi84BEw2wtseaA2DtysEVCUnJd7**

## Configure dapp address in node

Open config of node and configure dapp section.

Enable it.

It is advisable to disable automatic propagation during development.

After app is ready and tested then you can re enable it, so any further changes will be propagated to other nodes.

Config is in file config/config.inc.php

```
/**
* Configuration for decentralized apps
*/

//Set true to enable hosting of dapp
$_config['dapps']=true;

//Public key of verified dapp address
$_config['dapps_public_key']="PZ...ER";

//Private key of verified dapp address
$_config['dapps_private_key']="Lzhp...xtzM";

//Set if you do not want to update node with other dapps
$_config['dapps_anonymous']=false;

//If true you need to manually propagate dapp after changes
$_config['dapps_disable_auto_propagate']=true;
```

After setting config execute command to initialize dapp:

```
php cli/util.php propagate-dapps
```

This will create a new folder named with dapp address, and set permissions.

All files for your app you can create and update in this folder.

## Write dapp pages

Then in the dapp folder which is named by address you can write a complete web app as you normally do but with some restrictions and rules.

Our tutorial app will be simple because it has only one page accessible by user.

But we will make it in a separate folder so we can create in future more different apps under one address.

Lets call our app simple as a Labeler so we will create a folder where we will store app files.

We will have three files:
- index.php - index page visible for users
- api.php - file which will be called from index or form remote nodes
- functions.php - file with common functions

We will hold logged in users in session.

Using gateway auth we do not need to worry about the login process because gateway handles it through its own api. 

We just need to write calling functions.

When a user is logged in we can call dapp api to create, update or delete its label. 

For storing data we can use a node database or any other storage engine, but in our case we will use a simple json file which will be stored only on the owner node in a secure folder.

But as an example if you want truly decentralized storage then you will put this json file in the dapp folder so it will be propagated on other nodes. 

Also you can use any other decentralized storage solution. 

At the end when smart contracts are implemented then blockchain itself will be used for that.

Api functions will be considered node owned because they can execute only on node owner.

If another node executes it, it will automatically redirect and execute on the node owner.


In files there are explanations of what functions do.

The most important part is on top of the file, functions that initialize dapp and provide integration with a node.

Dapp files are executed in an isolated environment so there are some restrictions when it comes to writing code.

Reading a file system is only possible inside its own folder.

Executing system functions us disabled, so it is on the developer how to configure or overcome these rules.

On the other hand if a file is run on node owner it can then use protected node-only functions to execute code.

## Publish dapp

After we finish files we will publish dapp to the network and try it on any node.

If it is not enabled auto publish, when you finish building dapp you can publish it with command:

```
php cli/util.php propagate-dapps
```

It will be then propagated to other nodes.


