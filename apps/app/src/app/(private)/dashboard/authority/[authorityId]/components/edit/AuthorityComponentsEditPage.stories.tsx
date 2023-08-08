import { Meta, StoryFn } from '@storybook/react';

import { ComponentProps, StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindMainTitle } from '@aei/docs/.storybook/testing';
import { AsMainAgent as PrivateLayoutAsMainAgentStory } from '@aei/app/src/app/(private)/PrivateLayout.stories';
import {
  AuthorityComponentsEditPage,
  AuthorityComponentsEditPageContext,
} from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/components/edit/AuthorityComponentsEditPage';
import { Normal as CaseCompetentThirdPartyFieldNormalStory } from '@aei/app/src/components/CaseCompetentThirdPartyField.stories';
import { Normal as CaseDomainFieldNormalStory } from '@aei/app/src/components/CaseDomainField.stories';

type ComponentType = typeof AuthorityComponentsEditPage;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Pages/AuthorityComponentsEdit',
  component: AuthorityComponentsEditPage,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const commonComponentProps: ComponentProps<ComponentType> = {
  params: {
    authorityId: 'b79cb3ba-745e-5d9a-8903-4a02327a7e01',
  },
};

async function playFindTitle(canvasElement: HTMLElement): Promise<HTMLElement> {
  return await playFindMainTitle(canvasElement, /listes/i);
}

const Template: StoryFn<ComponentType> = (args) => {
  return <AuthorityComponentsEditPage {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  ...commonComponentProps,
};
NormalStory.parameters = {};
NormalStory.play = async ({ canvasElement }) => {
  await playFindTitle(canvasElement);
};

export const Normal = prepareStory(NormalStory, {
  childrenContext: {
    context: AuthorityComponentsEditPageContext,
    value: {
      ContextualCaseDomainField: CaseDomainFieldNormalStory,
      ContextualCaseCompetentThirdPartyField: CaseCompetentThirdPartyFieldNormalStory,
    },
  },
});

const WithLayoutStory = Template.bind({});
WithLayoutStory.args = {
  ...commonComponentProps,
};
WithLayoutStory.parameters = {
  layout: 'fullscreen',
};
WithLayoutStory.play = async ({ canvasElement }) => {
  await playFindTitle(canvasElement);
};

export const WithLayout = prepareStory(WithLayoutStory, {
  layoutStory: PrivateLayoutAsMainAgentStory,
  childrenContext: {
    context: AuthorityComponentsEditPageContext,
    value: {
      ContextualCaseDomainField: CaseDomainFieldNormalStory,
      ContextualCaseCompetentThirdPartyField: CaseCompetentThirdPartyFieldNormalStory,
    },
  },
});
