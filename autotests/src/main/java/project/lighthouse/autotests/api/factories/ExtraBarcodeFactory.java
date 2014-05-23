package project.lighthouse.autotests.api.factories;

import org.json.JSONException;
import project.lighthouse.autotests.api.abstractFactory.AbstractApiFactory;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.product.ExtraBarcode;
import project.lighthouse.autotests.objects.api.product.ExtraBarcodeArray;

import java.io.IOException;
import java.util.List;

/**
 * Factory to create product extra barcodes
 */
public class ExtraBarcodeFactory extends AbstractApiFactory {

    public ExtraBarcodeFactory(String userName, String password) {
        super(userName, password);
    }

    public void addProductExtraBarcodes(Product product, List<ExtraBarcode> extraBarcodes) throws JSONException, IOException {
        ExtraBarcodeArray extraBarcodeArray = new ExtraBarcodeArray(extraBarcodes);
        extraBarcodeArray.setProduct(product);
        String targetUrl = UrlHelper.getApiUrl(extraBarcodeArray.getApiUrl());
        getHttpRequestable().executePutRequest(targetUrl, extraBarcodeArray.getJsonObject());
    }
}
