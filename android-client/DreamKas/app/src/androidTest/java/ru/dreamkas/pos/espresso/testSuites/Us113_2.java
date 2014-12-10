package ru.dreamkas.pos.espresso.testSuites;

import ru.dreamkas.pos.R;
import ru.dreamkas.pos.espresso.steps.CommonSteps;
import ru.dreamkas.pos.espresso.steps.DrawerSteps;
import ru.dreamkas.pos.espresso.steps.LoginSteps;
import ru.dreamkas.pos.espresso.steps.StoreSteps;
import ru.dreamkas.pos.model.DrawerMenu;
import ru.dreamkas.pos.view.activities.WelcomeActivity_;

import static com.google.android.apps.common.testing.ui.espresso.matcher.ViewMatchers.isDisplayed;
import static ru.dreamkas.pos.espresso.EspressoHelper.waitForView;

public class Us113_2 extends BaseTestSuite<WelcomeActivity_> {
    @SuppressWarnings("deprecation")
    public Us113_2()
    {
        // This constructor was deprecated - but we want to support lower API levels.
        super("ru.crystals.vaverjanov.dreamkas.view", WelcomeActivity_.class);
    }

    //Scenario: Выбор магазина при первом старте
    public void testUserWillSeeKasFragmentWithSelectedStoreWhenChooseItInSpinner() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");

        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        changeCurrentActivity();
        StoreSteps.assertStore("Магазин №2");
    }

    //Scenario: Смена магазина
    public void testUserWillSeeKasFragmentWithSelectedStoreWhenUserHaveStoreAndSelectsNewStoreFromSpinner() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();
        changeCurrentActivity();

        DrawerSteps.select(DrawerMenu.AppStates.Store);

        StoreSteps.selectStore("Магазин №1");
        StoreSteps.assertStore("Магазин №1");
    }

    //Scenario: Магазин выбирается автоматически при старте если уже был выбран заранее
    public void testUserWillSeeKasFragmentWithSelectedStoreWhenUserHaveStore() throws Throwable {

        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.selectStore("Магазин №2"); changeCurrentActivity();

        CommonSteps.exitFromApp();

        Thread.sleep(2000);
        getActivity();
        waitForView(R.id.txtUsername, 10000, isDisplayed());
        LoginSteps.enterCredentialsAndClick("androidpos@lighthouse.pro", "lighthouse");
        StoreSteps.assertStore("Магазин №2");
    }
}
