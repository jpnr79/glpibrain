# GLPIBrain GLPI plugin

# GLPI Brain final degree project

This repository contains a final degree project in computer engineering.

Plugin for the open source IT management system GLPI, based on a neural network system that allows to classify incidents, identify patterns and recommend solutions.

The operation is performed through the llama3 api, to which requests are made to know the categorization and possible solution to the incidents that are not marked as solved in glpi.

## Requierements

It is required to have installed:

1. Web server that supports PHP (Apache2, Ngnix, lighttpd, Microsoft IIS).
2. PHP >= v8.3
3. GLPI >= 10.0 (https://glpi-install.readthedocs.io/en/latest/install/index.html)
4. Composer (https://getcomposer.org/doc/00-intro.md)
5. Docker
6. Relational database (MySQL, MariaDB...) (recomended MariaDB)
7. Extensiones de PHP recomendadas de GLPI (MySQLi, fileinfo, JSON...) ver https://glpi-install.readthedocs.io/en/latest/prerequisites.html
8. (Optional, only if your server has an Nvidia GPU) Nvidia container toolkit (https://docs.nvidia.com/datacenter/cloud-native/container-toolkit/latest/install-guide.html#installation)

## Installation

- Once GLPI is installed on your server, to install the plugin, clone the project or download the auto-generated zip, and unzip it in the plugin path of your glpi installation (usually located in /var/www/html/glpi/plugins).

- Then, run
```bash
composer.phar install
```

- Now pull the docker container from Ollama, depending if you have Nvidia GPU, the port by default i 11434:
```bash
docker run -d --gpus=all -v ollama:/root/.ollama -p 11434:11434 --name ollama ollama/ollama
```

- Or if wanted to run only with CPU:

```bash
docker run -d -v ollama:/root/.ollama -p 11434:11434 --name ollama ollama/ollama
```

- Finally pull the Llama model from Ollama with: (wait until it fully started up):

```bash
docker exec -it ollama ollama run llama3
```

## Troubleshooting

- P: I can't connect to the model.
- A: Check if firewall is blocking the 11434 port on your server and if it's running propperly.

- P: The plugin doesn't show up in glpi
- A: Check if it is enabled on plugins page. Refresh the page and wait for a few seconds, if still not showing up, move to another directory and move back again.

## Authors

- [@nselar](https://www.github.com/nselar)
- [@cmestre](https://www.github.com/cmestre)

## Contributing

- Open a ticket for each bug/feature so it can be discussed
- Follow [development guidelines](http://glpi-developer-documentation.readthedocs.io/en/latest/plugins/index.html)
- Refer to [GitFlow](http://git-flow.readthedocs.io/) process for branching
- Work on a new branch on your own fork
- Open a PR that will be reviewed by a developer
