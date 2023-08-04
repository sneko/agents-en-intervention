import { Meta, StoryFn } from '@storybook/react';
import { within } from '@storybook/testing-library';

import { StoryHelperFactory } from '@aei/docs/.storybook/helpers';
import { Normal as UpdateNoteFormNormalStory } from '@aei/app/src/app/(private)/dashboard/authority/[authorityId]/case/[caseId]/UpdateNoteForm.stories';
import { NodeCardContext, NoteCard } from '@aei/app/src/components/NoteCard';
import { notes } from '@aei/app/src/fixtures/case';
import { getTRPCMock } from '@aei/app/src/server/mock/trpc';

type ComponentType = typeof NoteCard;
const { generateMetaDefault, prepareStory } = StoryHelperFactory<ComponentType>();

export default {
  title: 'Components/NoteCard',
  component: NoteCard,
  ...generateMetaDefault({
    parameters: {},
  }),
} as Meta<ComponentType>;

const defaultMswParameters = {
  msw: {
    handlers: [
      getTRPCMock({
        type: 'mutation',
        path: ['removeNoteFromCase'],
        response: undefined,
      }),
    ],
  },
};

async function playFindElement(canvasElement: HTMLElement): Promise<HTMLElement> {
  return await within(canvasElement).findByText(/hello/i);
}

const Template: StoryFn<typeof NoteCard> = (args) => {
  return <NoteCard {...args} />;
};

const NormalStory = Template.bind({});
NormalStory.args = {
  note: notes[0],
};
NormalStory.parameters = { ...defaultMswParameters };
NormalStory.play = async ({ canvasElement }) => {
  await playFindElement(canvasElement);
};

export const Normal = prepareStory(NormalStory, {
  childrenContext: {
    context: NodeCardContext,
    value: {
      ContextualUpdateNoteForm: UpdateNoteFormNormalStory,
    },
  },
});
