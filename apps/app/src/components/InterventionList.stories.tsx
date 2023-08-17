import { Meta, StoryFn } from '@storybook/react';
import { within } from '@storybook/testing-library';

import { fetchApiEmployersEmployerIdinterventionsGetCollection } from '@aei/app/src/client/generated/components';
import { getApiMock } from '@aei/app/src/client/mock';
import { InterventionList } from '@aei/app/src/components/InterventionList';
import { admins } from '@aei/app/src/fixtures/admin';
import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';

type ComponentType = typeof InterventionList;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Components/InterventionList',
  component: InterventionList,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getApiMock<typeof fetchApiEmployersEmployerIdinterventionsGetCollection>({
        path: '/users/{userId}/interventions',
        response: [interventions[0], interventions[1], interventions[2]],
      }),
    ],
  },
};

const Template: StoryFn<ComponentType> = (args) => {
  return <InterventionList {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  admins: admins,
};
NormalStory.parameters = {
  ...defaultMswParameters,
};
NormalStory.play = async ({ canvasElement }) => {
  await within(canvasElement).findByRole('grid', {
    name: /liste/i,
  });
};

export const Normal = prepareStory(NormalStory);
