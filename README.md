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
| `DELIMITER` | you can change the default **DELIMITER** if it causes issue with your data.  | `$‎{{ }}`
| `GLOBAL_TEMPLATE_REPOSITORY` | you can set a global repository template where all the files are stored. | `false`

## :writing_hand: Syntax 
> :warning: To avoid rendering File Includes in this section, we have used `\!`. Make sure to use only `!` to render the file include.
> :warning: To avoid rendering Variables in this section, we added empty space after `$ `. Make sure to remove that space when using it

* Variables : `$‎{{ VARIABLE_NAME }}`
* File Includes
    * Inline : `<\!-- include {filepath} -->`
    * Reusable
        * Start : `<\!-- START include {filepath} -->`
        * END : `<\!-- END include {filepath} -->`
### Variables
All Default vairables exposed by github actions runner can be accessed like `$‎{{ GITHUB_ACTIONS }}` OR  `$‎{{ GITHUB_ACTOR }}`

**Dynamic Readme Github Action** Uses [**Repository Meta - Github Action**](https://github.com/varunsridharan/action-repository-meta) which 
exposes useful metadata as environment variable and those variables can be used as template tags.

any variables exposed by **Repository Meta** can be accessed like below
```
Repository Owner : $‎{{ env.REPOSITORY_OWNER }}
Repository Full Name : $‎{{ env.REPOSITORY_FULL_NAME }}
```

> :information_source: **Note :** Any environment variable can be accessed just by using `env.` as prefix `$‎{{ env.VARIABLE_NAME }}`

### File Includes
#### Source Options
* Relative Path : `template/file.md`
* Absolute path : `./template/file.md`
* From Repository : `{owner}/{repository}/{filepath}` OR `{owner}/{repository}@{branch}/{filepath}`

#### Relative Path Syntax 
Files are always searched from repository root path
```html
Inline Includes : 
<\!-- include template/file.md -->

Reusable Includes : 
<\!-- START template/file.md -->

<\!-- END template/file.md -->
```

#### Absolute path  Syntax 
Files are searched from current repository. This can come in handy when writing nested includesType a message
```html
Inline Includes : 
<\!-- include ./template/file.md -->

Reusable Includes : 
<\!-- START ./template/file.md -->

<\!-- END ./template/file.md -->
```

#### From Repository  Syntax 
You can include any type of file from any repository. If you want to include a file from a **Private Repository**, you have to provide **Github Personal Access** Token INSTEAD OF **Github Token** in the action's workflow file.
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


> :information_source: **Inline includes** can come in handy when you want to parse the data once and save it. It can also be used inside a nested include.
>
> :information_source: Even though **Reusable includes** and **Inline Includes** do the same work, they can come in handy when you are generating a template and saving it in the same file. It preserves the include comment which will be parsed again when re-generating the template, and the contents of the include will be updated accordingly.
>
> :warning: To avoid rendering File Includes in this section, we have used `\!`. Make sure to use only `!` to render the file include.

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
          GITHUB_TOKEN: $‎{{ secrets.GITHUB_TOKEN }}

      - name: "💫  Dynamic Template Render"
        uses: varunsridharan/action-dynamic-readme@main
        with:
          GLOBAL_TEMPLATE_REPOSITORY: {repository-owner}/{repository-name}
          files: |
            FILE.md
            FILE2.md=output_filename.md
            folder1/file.md=folder2/output.md
        env:
          GITHUB_TOKEN: $‎{{ secrets.GITHUB_TOKEN }}
```

---

<!-- START readme-templates/changelog.mustache -->

<!-- END readme-templates/changelog.mustache -->


<!-- START readme-templates/contributing.mustache -->

<!-- END readme-templates/contributing.mustache -->

<!-- START readme-templates/license-and-conduct.mustache -->

<!-- END readme-templates/license-and-conduct.mustache -->

<!-- START readme-templates/feedback.mustache -->

<!-- END readme-templates/feedback.mustache -->

<!-- START readme-templates/sponsor.mustache -->

<!-- END readme-templates/sponsor.mustache -->

<!-- START readme-templates/connect-and-say-hi.mustache -->

<!-- END readme-templates/connect-and-say-hi.mustache -->

<!-- START readme-templates/footer.mustache -->

<!-- END readme-templates/footer.mustache -->