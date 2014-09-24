package ru.crystals.vaverjanov.dreamkas.unit;

import android.test.InstrumentationTestCase;
import android.test.suitebuilder.annotation.SmallTest;
import junit.framework.Assert;
import ru.crystals.vaverjanov.dreamkas.controller.PreferencesManager;

public class PreferencesTest extends InstrumentationTestCase
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

        Assert.assertNull(preferences1.getCurrentStore(),preferences2.getCurrentStore());

        preferences1.setCurrentStore(storeId);

        Assert.assertEquals(preferences1.getCurrentStore(), preferences2.getCurrentStore(), storeId);

        preferences1 = initPreferences();
        Assert.assertEquals(preferences1.getCurrentStore(), preferences2.getCurrentStore(), storeId);

        preferences2.removeCurrentStore();

        Assert.assertNull(preferences1.getCurrentStore(),preferences2.getCurrentStore());
    }

    private PreferencesManager initPreferences()
    {
        PreferencesManager.initializeInstance(getInstrumentation().getContext());
        return PreferencesManager.getInstance();
    }
}
