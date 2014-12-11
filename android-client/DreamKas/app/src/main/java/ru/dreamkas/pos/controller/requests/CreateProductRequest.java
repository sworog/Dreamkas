package ru.dreamkas.pos.controller.requests;
import ru.dreamkas.pos.model.api.ProductApiObject;
import ru.dreamkas.pos.model.api.NamedObject;

public class CreateProductRequest extends BaseRequest<NamedObject>{
    private ProductApiObject mProduct;

    public CreateProductRequest(){
        super(NamedObject.class);
    }

    public void setProduct(ProductApiObject Product){
        this.mProduct = Product;
    }

    @Override
    public NamedObject loadDataFromNetwork() throws Exception
    {
        NamedObject store = mRestClient.createProduct(mProduct);
        return store;
    }
}
