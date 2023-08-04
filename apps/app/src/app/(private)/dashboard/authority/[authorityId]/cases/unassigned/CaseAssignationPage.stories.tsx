import { Meta, StoryFn } from '@storybook/react';
import { within } from '@storybook/testing-library';

import { ComponentProps, StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { AsMainAgent as PrivateLayoutAsMainAgentStory } from '@aei/app/src/app/(private)/PrivateLayout.stories';
import {
  CaseAssignationPage,
  CaseAssignationPageContext,
} from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/cases/unassigned/CaseAssignationPage';
import { Normal as UnassignedCaseSliderNormalStory } from '@aei/app/src/components/UnassignedCaseSlider.stories';
import { cases } from '@aei/app/src/fixtures/case';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof CaseAssignationPage;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Pages/CaseAssignation',
  component: CaseAssignationPage,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['assignCase'],
        response: { case: cases[0] },
      }),
    ],
  },
};

const commonComponentProps: ComponentProps<ComponentType> = {
  params: {
    authorityId: 'b79cb3ba-745e-5d9a-8903-4a02327a7e01',
  },
};

async function playFindElement(canvasElement: HTMLElement): Promise<HTMLElement> {
  return await within(canvasElement).findByRole('heading', {
    level: 1,
    name: /dossiers/i,
  });
}

const Template: StoryFn<ComponentType> = (args) => {
  return <CaseAssignationPage {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  ...commonComponentProps,
};
NormalStory.parameters = {
  testing: {
    timeToWaitAfterChangingThemeMs: 2000,
  },
  ...defaultMswParameters,
};
NormalStory.play = async ({ canvasElement }) => {
  await playFindElement(canvasElement);
};

export const Normal = prepareStory(NormalStory, {});

const WithLayoutStory = Template.bind({});
WithLayoutStory.args = {
  ...commonComponentProps,
};
WithLayoutStory.parameters = {
  layout: 'fullscreen',
  testing: {
    timeToWaitAfterChangingThemeMs: 2000,
  },
  ...defaultMswParameters,
};
WithLayoutStory.play = async ({ canvasElement }) => {
  await playFindElement(canvasElement);
};

export const WithLayout = prepareStory(WithLayoutStory, {
  layoutStory: PrivateLayoutAsMainAgentStory,
  childrenContext: {
    context: CaseAssignationPageContext,
    value: {
      ContextualUnassignedCaseSlider: UnassignedCaseSliderNormalStory,
    },
  },
});
