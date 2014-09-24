package ru.crystals.vaverjanov.dreamkas.espresso.tests.fragments;

import android.test.ActivityInstrumentationTestCase2;

import com.google.android.apps.common.testing.ui.espresso.Espresso;
import com.google.common.base.Preconditions;

import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.espresso.helpers.FragmentHelper;
import ru.crystals.vaverjanov.dreamkas.espresso.helpers.PreconditionHelper;
import ru.crystals.vaverjanov.dreamkas.espresso.helpers.RequestIdlingResource;
import ru.crystals.vaverjanov.dreamkas.model.api.NamedObject;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity_;
import ru.crystals.vaverjanov.dreamkas.view.StoreFragment;

import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.action.ViewActions.click;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withId;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;

public class StoresFragmentInstrumentationTest extends ActivityInstrumentationTestCase2<LighthouseDemoActivity_>
{
    private LighthouseDemoActivity mStartActivity;
    private StoreFragment mStoreFragment;

    @SuppressWarnings("deprecation")
    public StoresFragmentInstrumentationTest()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LighthouseDemoActivity_.class);
    }

    @Override
    protected void setUp() throws Exception
    {
        super.setUp();

        //Clear preferences and login
        PreconditionHelper ph = new PreconditionHelper();
        ph.clearPreference(getInstrumentation().getContext());
        setActivityIntent(ph.login(getInstrumentation().getContext()));

        //get activity and current fragment
        mStartActivity = getActivity();
        mStoreFragment = (StoreFragment)mStartActivity.getCurrentFragment();

        //register storesRequestListener idlingResource
        Espresso.registerIdlingResources(new RequestIdlingResource(mStoreFragment.storesRequestListener));

        //wait fragment trasactions
        FragmentHelper fh = new FragmentHelper(mStartActivity);
        fh.waitFragmentTransactionEnds();
    }

    @Override
    protected void tearDown() throws Exception
    {
        super.tearDown();
    }

    public void testUserWillSeeKasFragmentWithSelectedStoreWhenChooseItInSpinner() throws Exception
    {
        //wait for load stores
        //and then click on stores spinner
        Espresso.onView(withId(R.id.spStores)).perform(click());
        //check that spinner not empty
        Preconditions.checkState(mStoreFragment.spStores.getAdapter().getCount() > 0, "No have any stores");

        NamedObject item = (NamedObject)mStoreFragment.spStores.getItemAtPosition(0);

        //click on first item in spinner
        Espresso.onView(withText(item.getName())).perform(click());
        //click on save button
        Espresso.onView(withId(R.id.btnSaveStoreSettings)).perform(click());
        //user will see kas fragment with storeId text on it
        onView(withId(R.id.lblStore)).check(matches(withText(item.getId())));
    }
}