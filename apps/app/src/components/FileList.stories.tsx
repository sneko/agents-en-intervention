import { Meta, StoryFn } from '@storybook/react';
import { within } from '@storybook/testing-library';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { FileList } from '@aei/app/src/components/FileList';
import { uiAttachments } from '@aei/app/src/fixtures/attachment';

type ComponentType = typeof FileList;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Components/FileList',
  component: FileList,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const Template: StoryFn<ComponentType> = (args) => {
  return <FileList {...args} />;
};

const SampleStory = Template.bind({});
SampleStory.args = {
  files: uiAttachments,
  onRemove: async () => {},
};
SampleStory.parameters = {};
SampleStory.play = async ({ canvasElement }) => {
  await within(canvasElement).findAllByRole('button', {
    name: /supprimer/i,
  });
};

export const Sample = prepareStory(SampleStory);
