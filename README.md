# Groundhogg-Slack

A simple extension for Groundhogg that allows you to add and remove users from your Slack workspace, in addition to adding and removing them from specific channels.

This free extension is built by Colin Longworth from WooNinja.io

## Important - Read Me First

This extension requires advanced set up in Slack and Groundhogg. Users should be familiar with Slack and Groundhogg before using this extension. 

## Heads Up! - Inviting & Removing Users in Slack

To invite or remove users, you are required to use Enterprise Grid from Slack. This is generally only available if you have a paid account. To use these features, ensure you app includes the following scope:

- admin.users:write 

## Help & Support

This extension is offered for free and without warranty or support. I do not offer any support for set up and configuration of this extension. For bugs and feature requests, please open an Issue or make a PR on this repo.

## Set Up & Installation

1. Create an App in Slack @ https://api.slack.com/apps

2. Under OAuth & Permissions, add the following scopes:

- admin
- channels:read
- users:read
- users:read.email
- admin.users:write **Only use if you have Slack Enterprise Grid!**

3. Click Install App, then take note of your Token on the OAuth & Permissions page.

4. Install this plugin in WordPress and activate it.

5. In Groundhogg, choose Settings then Slack. Enter your Token and click Save Changes

6. Open your Slack workspace in a web browser and examine the URL. Look for something like 'T011B1XXXX'. This is your Team ID.

7. Enter your Team ID in the applicable box in your Groundhogg Settings and click Save Changes.

8. You are now ready to use Slack with Groundhogg!
