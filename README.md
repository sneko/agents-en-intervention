# Agents en intervention

<!-- TODO -->

## Usage

This monorepository is managed with `pnpm`, have it installed and run:

```
pnpm install
```

Then you can have a look at the `Makefile` file to see common available commands.

For example to start developing, launch the application and the Storybook with:

```
make serve
```

If you want to only launch one of the three:

- Frontend: `cd apps/app && pnpm dev`
- Backend: `cd apps/app && TODO`
- Storybook: `cd apps/docs && pnpm dev`

**Note the application needs some tools to run locally:**

- the database
- a mail catcher to prevent using a real SMTP
- a mail catcher to prevent using a real SMTP

The easiest way to do so is by using `docker-compose`. In another terminal just set up all tools:

```
docker-compose up
```

And when calling it a day you can stop those containers with:

```
docker-compose down
```

That's it! But still, we advise you to read the documentation below to better understand how the project is structured.

## Technical setup

<!-- TODO -->

### AlwaysData

This helps for DNS delegation (to configure domains, emails...). Configuration steps will be specified below for each service that needs specific DNS records.

### GitHub

#### Default branch

The default branch is `dev`.

#### Branch protection rules

1.  Pattern: `main`
    Checked:

    - Require status checks to pass before merging
    - Do not allow bypassing the above settings

2.  Pattern: `dev`
    Checked:

    - Require linear history
    - Do not allow bypassing the above settings
    - Allow force pushes (+ "Specify who can force push" and leave for administrators)

### IDE

Since the most used IDE as of today is Visual Studio Code we decided to go we it. Using it as well will make you benefit from all the settings we set for this project.

#### Manual steps

Every settings should work directly when opening the project with `vscode`, except for TypeScript.

Even if your project uses a TypeScript program located inside your `node_modules`, the IDE generally uses its own. Which may imply differences since it's not the same version. We do recommend using the exact same, for this it's simple:

1. Open a project TypeScript file
2. Open the IDE command input
3. Type `TypeScript` and click on the item `TypeScript: Select TypeScript Version...`
4. Then select `Use Workspace Version`

In addition, using the workspace TypeScript will load `compilerOptions.plugins` specified in your `tsconfig.json` files, which is not the case otherwise. Those plugins will bring more confort while developing!

### Tips
#### Frontend development

##### i18n

Currently we only use i18n to help displaying ENUM values. We use the `i18n Ally` VSCode extension to improve a bit the usage but everything can be written manually in `.json` files :)

##### Storybook

###### Use it first

Developing a UI component by launching the whole application is annoying because you may have to do specific interactions to end viewing the right state of the component you are building.

We advise when doing some UI to only run Storybook locally, so you do not worry about other stuff. You can also mock your API calls! At the end splitting your component into different stories makes easy for non-technical people of the team to view deepest details. They will be able to review the visual changes with Chromatic, and also from the Storybook they will save too their time avoiding complex interactions to see specific state of the application.

It's not magical, it does not replace unit and end-to-end testing, but it helps :)

###### Advantages

- Accessibility reports
- See almost all tests of your application
- Helps architecturing your components split
- Their rendering is tested in the CI/CD, so it's very likely your components are valid at runtime

###### How we test stories

You can do UI testing scoped to components (I mean, not full end-to-end), and if so, it's recommended to reuse the stories to benefit from their mocking set up.

A decision we took to keep in mind: it's common to create a `.test.js` file to test that a component renders correctly (thanks to Jest, Playwright or Cypress), but since we have "Storybook test runners" already in place that pop each component, we feel it's better to use the story `play` function concept (also used by the interaction addon) to make sure they are rendering correctly.

- It avoids rendering each component one more time
- It saves 1 file and it's clearer since from Storybook you can see in the "Interactions" tab if the expected behavior is matched

Those kind of tests may be useful to:

- Make sure for an atomic component the data is displayed correctly (but it's likely the work of your team to review visual changes)
- Guarantee the sequences when mocking (e.g. first a loader, then the form is displayed), it helps also the "Storybook test runners" to wait the right moment to take a screenshot/snapshot of the final state (through Chromatic in this case), not intermediate ones since the runner doesn't know when the component is fully loaded otherwise

_(in case you have specific needs of testing that should not pollute the Storybook stories, go with a `.test.js` file, see examples [here](https://storybook.js.org/docs/react/writing-tests/importing-stories-in-tests))_

_Tip: during the testing you could `findByText` but it's recommended to `findByRole`. It helps thinking and building for accessibility from scratch (because no, accessibility is not done/advised by an accessibility automated check unfortunately)._

###### Testing performance

During the test we render the story, we test the light theme accessibility and then we switch to the dark theme to test it too. The re-rendering for color change over the entire DOM is unpredictable, it depends on the CPU saturation. We had some leakage of previous theme rendered over the new one.

We evaluated 2 solutions:

- limit the number of Jest workers with `--maxWorkers`
- increase the amount of time we wait after triggering a theme change

After dozens of tests it appears the most reliable and the fastest is by keeping parallelism (no worker limitation), but increase the amount of time. But this latter depends on the environment:

- when testing headless: there is less work done, the delay is shorter
- when testing with a browser rendering: it is in higher demand, to avoid color style leakage we need to increase the delay (tests duration is +50%, which is fine)

##### Hydratation issue

When developing a frontend it's likely you will have client hydratation according to the server content. It will fail if some browser extensions are enabled and modify the DOM. You need to identify the source of the issue and then, either disable the extension, or request it to not modify the DOM when developing on `http://localhost:xxxx/`.

From our experience, this can be caused by:

- Password managers _(make sure to have no credentials that match your development URL)_
- Cookie banner automatic rejection _(in their settings you're likely to be able to exclude your development URL from being analyzed)_

_(in React the error was `Extra attributes from the server: xxxxx`)_

##### Cannot fetch specific files

As for any `hydratation issue` it worths taking a look at your browser extensions, some may block outgoing requests.

For example:

- Ad blockers _(whitelist the blocked URL in your extension)_

#### Jest not working in VSCode

Sometimes it appears Jest VSCode extension will be stuck and will keep throwing:

```
env: node: No such file or directory
```

We found no fix. The only solution is to relaunch VSCode, and if it still doesn't work, try to close entirely VSCode and open it through your terminal with a something like:

```sh
code /Users/xxxxx/yyyyy/agents-en-intervention
```

#### Podman (Docker replacement)

In case you use `podman` instead of `docker`, it's possible `testcontainers` won't work out of the box. You need to tell where to find `podman` socket. For this just use `podman machine inspect --format '{{.ConnectionInfo.PodmanSocket.Path}}'` and copy the provided path.

Then in your `./apps/app/.env.jest.local` (create one if needed) add the following while replacing `${PATH_TO_PASTE}` accordingly:

```
export DOCKER_HOST=unix://${PATH_TO_PASTE}
```

_(If you are on MacOS also set in this file `export TESTCONTAINERS_RYUK_DISABLED=true` because Ryuk for `testcontainers` results in "operation not supported" errors as of now)_

#### Formatting documents for compliance

Legal documents are mainly written out of technical scope in a basic text editor, and they may be updated quite often. Either you host them on a Markdown website or you embed them as HTML in your website. For both you have to maintain some transformations and you probably don't want to scan in detail for each modification, ideally you just want to redo all at once to be sure there will be no missing patch.

In this repository you can use `./apps/app/format-legal-documents.sh` to transform the initial `.docx` files `.html` files:

- No matter the name of the file it will convert it (though 1 per folder)
- It allows to collaborate on Word-like software (mainly used by legal people)

## Technical architecture document

It has been written into [TECHNICAL_ARCHITECTURE_DOCUMENT.md](./TECHNICAL_ARCHITECTURE_DOCUMENT.md).
