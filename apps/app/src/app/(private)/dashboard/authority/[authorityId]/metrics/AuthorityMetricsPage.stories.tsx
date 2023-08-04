import { Meta, StoryFn } from '@storybook/react';

import { ComponentProps, StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { playFindMainTitle } from '@aei/docs/.storybook/testing';
import { AsMainAgent as PrivateLayoutAsMainAgentStory } from '@aei/app/src/app/(private)/PrivateLayout.stories';
import { AuthorityMetricsPage } from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/metrics/AuthorityMetricsPage';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof AuthorityMetricsPage;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Pages/AuthorityMetrics',
  component: AuthorityMetricsPage,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['generateCsvFromCaseAnalytics'],
        response: {
          attachment: {
            id: '13422339-278f-400d-9b25-5399e9fe6233',
            url: 'https://people.sc.fsu.edu/~jburkardt/data/csv/snakes_count_10.csv',
          },
        },
      }),
    ],
  },
};

const commonComponentProps: ComponentProps<ComponentType> = {
  params: {
    authorityId: 'b79cb3ba-745e-5d9a-8903-4a02327a7e01',
  },
};

async function playFindTitle(canvasElement: HTMLElement): Promise<HTMLElement> {
  return await playFindMainTitle(canvasElement, /statistiques/i);
}

const Template: StoryFn<ComponentType> = (args) => {
  return <AuthorityMetricsPage {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  ...commonComponentProps,
};
NormalStory.parameters = { ...defaultMswParameters };
NormalStory.play = async ({ canvasElement }) => {
  await playFindTitle(canvasElement);
};

export const Normal = prepareStory(NormalStory, {});

const WithLayoutStory = Template.bind({});
WithLayoutStory.args = {
  ...commonComponentProps,
};
WithLayoutStory.parameters = {
  layout: 'fullscreen',
  ...defaultMswParameters,
};
WithLayoutStory.play = async ({ canvasElement }) => {
  await playFindTitle(canvasElement);
};

export const WithLayout = prepareStory(WithLayoutStory, {
  layoutStory: PrivateLayoutAsMainAgentStory,
});
