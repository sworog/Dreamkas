package ru.dreamkas.pos.unit;

import android.test.AndroidTestCase;
import android.test.InstrumentationTestCase;
import android.test.suitebuilder.annotation.SmallTest;
import junit.framework.Assert;

import ru.dreamkas.pos.controller.PreferencesManager;

import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.assertThat;
import static org.hamcrest.core.AllOf.allOf;
import static org.hamcrest.core.Is.is;

public class PreferencesTest extends AndroidTestCase
{
    @Override
    protected void setUp() throws Exception
    {
        super.setUp();
    }

    @SmallTest
    public void test_empty_getInstance() throws Exception
    {
        try
        {
            PreferencesManager.initializeInstance(null);
            PreferencesManager preferences = PreferencesManager.getInstance();
            Assert.fail("Should have thrown IllegalStateException");
        }
        catch(IllegalStateException e)
        {
            //success
        }
    }

    @SmallTest
    public void test_getSetCurrentStore() throws Exception
    {
        String storeId = "test_store_id_string";

        PreferencesManager preferences1 = initPreferences();
        PreferencesManager preferences2 = initPreferences();

        preferences1.clear();

        assertThat("Stores extracted from cleared preferences should be null", null, allOf(is(preferences1.getCurrentStore()), is(preferences2.getCurrentStore())));

        preferences1.setCurrentStore(storeId);

        assertThat("Stores extracted from preferences should be equals to origin", storeId, allOf(is(preferences1.getCurrentStore()), is(preferences2.getCurrentStore())));

        preferences1 = initPreferences();

        assertThat("Stores extracted from new instance of preferences should be equals with previous and origin", storeId, allOf(is(preferences1.getCurrentStore()), is(preferences2.getCurrentStore())));

        preferences2.removeCurrentStore();

        assertThat("After store removal  in one instance of preferences should, other should be removed too", null, allOf(is(preferences1.getCurrentStore()), is(preferences2.getCurrentStore())));
    }

    private PreferencesManager initPreferences()
    {
        PreferencesManager.initializeInstance(getContext());
        return PreferencesManager.getInstance();
    }
}
