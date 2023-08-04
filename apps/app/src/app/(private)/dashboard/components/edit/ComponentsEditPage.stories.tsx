import { Meta, StoryFn } from '@storybook/react';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindMainTitle } from '@aei/docs/.storybook/testing';
import { AsAdmin as PrivateLayoutAsAdminStory } from '@aei/app/src/app/(private)/PrivateLayout.stories';
import { ComponentsEditPage, ComponentsEditPageContext } from '@aei/app/src/app/(private)/dashboard/components/edit/ComponentsEditPage';
import { Normal as CaseCompetentThirdPartyFieldNormalStory } from '@aei/app/src/components/CaseCompetentThirdPartyField.stories';
import { Normal as CaseDomainFieldNormalStory } from '@aei/app/src/components/CaseDomainField.stories';

type ComponentType = typeof ComponentsEditPage;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Pages/ComponentsEdit',
  component: ComponentsEditPage,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

async function playFindTitle(canvasElement: HTMLElement): Promise<HTMLElement> {
  return await playFindMainTitle(canvasElement, /listes/i);
}

const Template: StoryFn<ComponentType> = (args) => {
  return <ComponentsEditPage {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {};
NormalStory.parameters = {};
NormalStory.play = async ({ canvasElement }) => {
  await playFindTitle(canvasElement);
};

export const Normal = prepareStory(NormalStory, {
  childrenContext: {
    context: ComponentsEditPageContext,
    value: {
      ContextualCaseDomainField: CaseDomainFieldNormalStory,
      ContextualCaseCompetentThirdPartyField: CaseCompetentThirdPartyFieldNormalStory,
    },
  },
});

const WithLayoutStory = Template.bind({});
WithLayoutStory.args = {};
WithLayoutStory.parameters = {
  layout: 'fullscreen',
};
WithLayoutStory.play = async ({ canvasElement }) => {
  await playFindTitle(canvasElement);
};

export const WithLayout = prepareStory(WithLayoutStory, {
  layoutStory: PrivateLayoutAsAdminStory,
  childrenContext: {
    context: ComponentsEditPageContext,
    value: {
      ContextualCaseDomainField: CaseDomainFieldNormalStory,
      ContextualCaseCompetentThirdPartyField: CaseCompetentThirdPartyFieldNormalStory,
    },
  },
});
