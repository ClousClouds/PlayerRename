# PlayerRename
PlayerRename is a plugin that allows server administrators to change a player's in-game name. This plugin provides two main commands: `/rename <newName>` for changing a player's name and `/listrenamed` (available only to OP players) to list all players who have changed their names.

## Features
- **/rename <newName>**: Allows players or admins to change their in-game name.
- **/listrenamed**: Lists all players who have changed their name (only accessible by OP players).

## Commands

### `/rename <newName>`
- **Description**: Changes your in-game name to the specified `newName`.
- **Permission**: Default (any player can use it unless restricted by the server).
- **Usage**: /rename <newName>

### `/listrenamed`
- **Description**: Displays a list of players who have changed their names.
- **Permission**: Only OP players can use this command.
- **Usage**: /listrenamed

## Installation
1. Download the `PlayerRename` plugin.
2. Place the `.phar` file into the `plugins` directory of your server.
3. Restart or reload your server to enable the plugin.

## Configuration
The plugin does not require any additional configuration out of the box. However, you can modify permissions or command restrictions via your server's permission management system.

## Dependencies
- None

## License
This plugin is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
