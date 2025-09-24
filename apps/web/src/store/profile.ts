import { create } from 'zustand';
import type { Profile } from '../lib/types';
import { profileService } from '../services/profile';

type State = {
  profile: Profile | null;
  loading: boolean;
  error: string | null;
};

type Actions = {
  fetch: () => Promise<void>;
  reset: () => void;
};

export const useProfileStore = create<State & Actions>((set) => ({
  profile: null,
  loading: false,
  error: null,
  async fetch() {
    set({ loading: true, error: null });
    try {
      const data = await profileService.get();
      set({ profile: data, loading: false });
    } catch (e: any) {
      set({ error: e?.message ?? 'Error', loading: false });
    }
  },
  reset() {
    set({ profile: null, loading: false, error: null });
  },
}));

