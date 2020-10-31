<p align="center"><img src="https://cdn.svarun.dev/gh/actions.png" width="150px"/></p>

# Dynamic ReadMe - ***Github Action***
 Convert Static Readme into Dynamic Readme 

## Why 🤔 ?
As an open-source software developer I use GitHub Repositories extensively to store my projects. I maintain over 100 projects, of which, about 85% of them have standardised content for the README.md file. That being said, I am finding it increasingly tedious to add, update or remove content in the README.md files across all my repositories because of two main challenges:

1. Templating of files: The information which is common to README.md files across all my repositories such as Sponsor, Contribute, Contact, etc., cannot be templated and inserted into the README.md files of all my projects / repositories.

2. Project / Repository-specific information: Github does not provide any repository-specific variables which can be used to dynamically insert repository information into the README.md file. As a result, repository-specific information needs to be hard-coded into the README file.

### Solution:
To overcome this limitation, and help developers such as myself automate this tedious task, I have created a GitHub action called “Github Action Dynamic ReadMe”. This action pulls repository-specific variables and also allows for templating of files, thereby easily creating dynamic file content for files such as README.md.


## ⚙️ Configuration
| Option | Description | Default |
| --- | --- | --- |
| `FILES` | list of files that should be compiled.  | `false`
| `DELIMITER` | you can change the default **DELIMITER** if it causes issue with your data.  | `${{ }}`
| `GLOBAL_TEMPLATE_REPOSITORY` | you can set a global repository template where all the files are stored. | `false`

## :writing_hand: Syntax 
> :warning: To avoid rendering File Includes in this section. we have used `\!`. make sure to use only `!` to render the file include.
* Variables : `${{\ VARIABLE_NAME }}`
* File Includes
    * Inline : `<\!-- include {filepath} -->`
    * Reusable
        * Start : `<\!-- START include {filepath} -->`
        * END : `<\!-- END include {filepath} -->`
### Variables
All Default vairables exposed by github actions runner can be accessed like `${{ GITHUB_ACTIONS }}` OR  `${{ GITHUB_ACTOR }}`

**Dynamic Readme Github Action** Uses [**Repository Meta - Github Action**](https://github.com/varunsridharan/action-repository-meta) which 
exposes useful metadata as environment variable and those variables can be used as template tags.

any variables exposed by **Repository Meta** can be accessed like below
```
Repository Owner : ${{ env.REPOSITORY_OWNER }}
Repository Full Name : ${{ env.REPOSITORY_FULL_NAME }}
```

> :information_source: **Note :** Any environment variable can be accessed just by using `env.` as prefix `${{ env.VARIABLE_NAME }}`

### File Includes
#### Source Options
* Relative Path : `template/file.md` --  
* Absolute path : `./template/file.md` -- 
* From Repository : `{owner}/{repository}/{filepath}` OR `{owner}/{repository}@{branch}/{filepath}`

#### Relative Path Syntax 
Files are always search from repository root path
```html
Inline Includes : 
<\!-- include template/file.md -->

Reusable Includes : 
<\!-- START template/file.md -->

<\!-- END template/file.md -->
```

#### Absolute path  Syntax 
Files are search from current repository this can come in handy when writing with nested includes
```html
Inline Includes : 
<\!-- include ./template/file.md -->

Reusable Includes : 
<\!-- START ./template/file.md -->

<\!-- END ./template/file.md -->
```

#### From Repository  Syntax 
You can include any type of file from any repository. if you want to include from a **Private** Repository then you have to provide **Github Personal Access Token** Instead **Github Token** in action's workflow file

> :information_source: If branch is not specified then default branch will be cloned

##### Without Branch
```html
Inline Includes : 
<\!-- include octocat/Spoon-Knife/README.md -->

Reusable Includes : 
<\!-- START octocat/Spoon-Knife/README.md -->

<\!-- END octocat/Spoon-Knife/README.md -->
```
##### Custom Branch
```html
Inline Includes : 
<\!-- include octocat/Spoon-Knife/@master/README.md -->

Reusable Includes : 
<\!-- START octocat/Spoon-Knife/@master/README.md -->

<\!-- END octocat/Spoon-Knife/@master/README.md -->
```


> :information_source: **Inline includes** can come in handy when you want to parse the data once and save it. or can be used inside a nested includes
>
> :information_source: **Reusable includes** & Inline Includes dose the same work. but this can come in handy when you are generating template & saving it in the same file it preserves include comment and will be parsed again when Re-generating the template & contents of that include will be updated
>
> :warning: To avoid rendering File Includes in this section. we have used `\!`. make sure to use only `!` to render the file include.

---
### For live Demo Please Check [Demo Repository](https://github.com/varunsridharan/demo-dynamic-readme)
---

## 🚀 Example Workflow File

```yaml
name: Dynamic Template

on:
  push:
    branches:
      - main
  workflow_dispatch:

jobs:
  update_templates:
    name: "Update Templates"
    runs-on: ubuntu-latest
    steps:
      - name: "📥  Fetching Repository Contents"
        uses: actions/checkout@main

      - name: "💾  Github Repository Metadata"
        uses: varunsridharan/action-repository-meta@main
        env:
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}

      - name: "💫  Dynamic Template Render"
        uses: varunsridharan/action-dynamic-readme@main
        with:
          GLOBAL_TEMPLATE_REPOSITORY: {repository-owner}/{repository-name}
          files: |
            templates/variables/defaults.md=output/variables/defaults.md
            templates/file-includes/inline.md=output/file-includes/inline.md
            templates/file-includes/reusable-includes.md=output/file-includes/reusable-includes.md
        env:
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
```

---

## 📝 Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

[Checkout CHANGELOG.md](/CHANGELOG.md)

## 🤝 Contributing
If you would like to help, please take a look at the list of [issues](issues/).

## 💰 Sponsor
[I][twitter] fell in love with open-source in 2013 and there has been no looking back since! You can read more about me [here][website].
If you, or your company, use any of my projects or like what I’m doing, kindly consider backing me. I'm in this for the long run.

- ☕ How about we get to know each other over coffee? Buy me a cup for just [**$9.99**][buymeacoffee]
- ☕️☕️ How about buying me just 2 cups of coffee each month? You can do that for as little as [**$9.99**][buymeacoffee]
- 🔰         We love bettering open-source projects. Support 1-hour of open-source maintenance for [**$24.99 one-time?**][paypal]
- 🚀         Love open-source tools? Me too! How about supporting one hour of open-source development for just [**$49.99 one-time ?**][paypal]

## 📜  License & Conduct
- [**General Public License v3.0 license**](LICENSE) © [Varun Sridharan](website)
- [Code of Conduct](code-of-conduct.md)

## 📣 Feedback
- ⭐ This repository if this project helped you! :wink:
- Create An [🔧 Issue](issues/) if you need help / found a bug

## Connect & Say 👋
- **Follow** me on [👨‍💻 Github][github] and stay updated on free and open-source software
- **Follow** me on [🐦 Twitter][twitter] to get updates on my latest open source projects
- **Message** me on [📠 Telegram][telegram]
- **Follow** my pet on [Instagram][sofythelabrador] for some _dog-tastic_ updates!

---

<p align="center">
<i>Built With ♥ By <a href="https://sva.onl/twitter"  target="_blank" rel="noopener noreferrer">Varun Sridharan</a> <a href="https://en.wikipedia.org/wiki/India"><img src="https://cdn.svarun.dev/flag-india-flat.svg" width="20px"/></a> </i> <br/><br/>
<img src="https://s.w.org/style/images/codeispoetry.png"/>
</p>

---

<!-- Personl Links -->
[paypal]: https://sva.onl/paypal
[buymeacoffee]: https://sva.onl/buymeacoffee
[sofythelabrador]: https://www.instagram.com/sofythelabrador/
[github]: https://sva.onl/github/
[twitter]: https://sva.onl/twitter/
[telegram]: https://sva.onl/telegram/
[email]: https://sva.onl/email
[website]: https://sva.onl/website/
