package ru.crystals.vaverjanov.dreamkas.espresso.tests.fragments;

import android.test.ActivityInstrumentationTestCase2;
import com.google.android.apps.common.testing.ui.espresso.Espresso;
import org.hamcrest.CoreMatchers;
import ru.crystals.vaverjanov.dreamkas.R;
import ru.crystals.vaverjanov.dreamkas.controller.PreferencesManager;
import ru.crystals.vaverjanov.dreamkas.espresso.helpers.FragmentHelper;
import ru.crystals.vaverjanov.dreamkas.espresso.helpers.NetworkManager;
import ru.crystals.vaverjanov.dreamkas.espresso.helpers.RequestIdlingResource;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity;
import ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity_;
import ru.crystals.vaverjanov.dreamkas.view.StoreFragment;
import static com.google.android.apps.common.testing.ui.espresso.Espresso.onView;
import static com.google.android.apps.common.testing.ui.espresso.assertion.ViewAssertions.matches;
import static com.google.android.apps.common.testing.ui.espresso.matcher.RootMatchers.withDecorView;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.withText;
import static org.hamcrest.core.Is.is;

public class StoresFragmentOfflineInstrumentationTest extends ActivityInstrumentationTestCase2<LighthouseDemoActivity_>
{
    private LighthouseDemoActivity mStartActivity;
    private StoreFragment mStoreFragment;
    private NetworkManager networkManager;


    @SuppressWarnings("deprecation")
    public StoresFragmentOfflineInstrumentationTest()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", LighthouseDemoActivity_.class);
    }

    @Override
    protected void setUp() throws Exception
    {
        super.setUp();

        //clear preferences
        PreferencesManager.initializeInstance(getInstrumentation().getContext());
        PreferencesManager preferences = PreferencesManager.getInstance();
        preferences.clear();

        mStartActivity = getActivity();

        //disable wifi
        networkManager = new NetworkManager(mStartActivity);
        networkManager.changeWifiState(false);

        //get fragment and register idle resource for request listener
        mStoreFragment = (StoreFragment)mStartActivity.getCurrentFragment();
        Espresso.registerIdlingResources(new RequestIdlingResource(mStoreFragment.storesRequestListener));

        //wait for fragment transactions ends
        FragmentHelper fh = new FragmentHelper(mStartActivity);
        fh.waitFragmentTransactionEnds();
    }

    @Override
    protected void tearDown() throws Exception
    {
        //turn on wifi after all
        networkManager.changeWifiState(true);
        super.tearDown();
    }

    public void testUserWillGetToastWithErrorWhenOffline() throws Exception
    {
        //Wait for toast with error text
        onView(withText("Network is not available")).inRoot(withDecorView(CoreMatchers.not(is(getActivity().getWindow().getDecorView())))).check(matches(isDisplayed()));
    }

    public void testEmptySpinnerView() throws Exception
    {
        //wait for empty placeholder instead stores spinner
        onView(withText(R.string.empty_list)).check(matches(isDisplayed()));
    }
}