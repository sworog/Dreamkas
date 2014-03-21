package project.lighthouse.autotests.pages.errorPage;

import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;

/**
 * Page representing error page container (403, 404)
 */
public class ErrorPage extends CommonPageObject {

    @FindBy(xpath = "//*[contains(@class, 'page_error_404')]")
    @SuppressWarnings("unused")
    private WebElement pageError404;

    @FindBy(xpath = "//*[contains(@class, 'page_error_403')]")
    @SuppressWarnings("unused")
    private WebElement pageError403;

    public ErrorPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public WebElement getPageError404WebElement() {
        return pageError404;
    }

    public WebElement getPageError403WebElement() {
        return pageError403;
    }
}
