package project.lighthouse.autotests.common;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.ApiConnect;

public class CommonApi extends PageObject {

    protected ApiConnect apiConnect = new ApiConnect(getDriver());

    public CommonApi(WebDriver driver) {
        super(driver);
    }
}
