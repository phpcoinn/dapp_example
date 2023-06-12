# Example of decentralized app

Here is a tutorial on how you can create and deploy your own dapp on php coin network.

As an example we will create an app that login users to blockchain with gateway api and allows them to check balance (public API access).

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
