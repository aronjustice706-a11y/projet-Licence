
                            <!-- Tab panes -->
                            <div class="tab-content">
                              <!-- Reactifs de Laboratoire -->
                              <div class="tab-pane fade in mt-2 active show" id="reactifsdelaboratoire">
                                  <div class="row">
                                  <?php  
                                    $query = 'SELECT * FROM product WHERE CATEGORY_ID=0 GROUP BY PRODUCT_CODE ORDER by PRODUCT_CODE ASC';
                                        $result = mysqli_query($db, $query);
                                    if ($result && mysqli_num_rows($result) > 0):
                                                while($product = mysqli_fetch_assoc($result)):
                                      ?>
                                  <div class="col-sm-4 col-md-2">
                                      <form method="post" action="pos.php?action=add&id=<?php echo $product['PRODUCT_ID']; ?>">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $product['NAME']; ?></h6>
                                        <h6><?php echo number_format($product['PRICE']); ?> XAF</h6>
                                              <input type="text" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="name" value="<?php echo $product['NAME']; ?>" />
                                              <input type="hidden" name="price" value="<?php echo $product['PRICE']; ?>" />
                                        <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info" value="Ajouter" />
                                          </div>
                                      </form>
                                  </div>
                                  <?php endwhile; else: ?>
                                  <div class="col-12">
                                    <p class="text-muted text-center">Aucun produit dans cette catégorie</p>
                                    </div>
                                  <?php endif; ?>
                                </div>
                              </div>

                              <!-- Appareil de Laboratoire -->
                              <div class="tab-pane fade in mt-2" id="appareildelaboratoire">
                                <div class="row">
                                            <?php
                                    $query = 'SELECT * FROM product WHERE CATEGORY_ID=1 GROUP BY PRODUCT_CODE ORDER by PRODUCT_CODE ASC';
                                        $result = mysqli_query($db, $query);
                                    if ($result && mysqli_num_rows($result) > 0):
                                                while($product = mysqli_fetch_assoc($result)):
                                      ?>
                                  <div class="col-sm-4 col-md-2">
                                      <form method="post" action="pos.php?action=add&id=<?php echo $product['PRODUCT_ID']; ?>">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $product['NAME']; ?></h6>
                                        <h6><?php echo number_format($product['PRICE']); ?> XAF</h6>
                                              <input type="text" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="name" value="<?php echo $product['NAME']; ?>" />
                                              <input type="hidden" name="price" value="<?php echo $product['PRICE']; ?>" />
                                        <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info" value="Ajouter" />
                                          </div>
                                      </form>
                                  </div>
                                  <?php endwhile; else: ?>
                                  <div class="col-12">
                                    <p class="text-muted text-center">Aucun produit dans cette catégorie</p>
                                    </div>
                                  <?php endif; ?>
                                </div>
                              </div>

                              <!-- Solutions de Laboratoire -->
                              <div class="tab-pane fade in mt-2" id="solutionsdelaboratoire">
                                  <div class="row">
                                  <?php  
                                    $query = 'SELECT * FROM product WHERE CATEGORY_ID=2 GROUP BY PRODUCT_CODE ORDER by PRODUCT_CODE ASC';
                                        $result = mysqli_query($db, $query);
                                    if ($result && mysqli_num_rows($result) > 0):
                                                while($product = mysqli_fetch_assoc($result)):
                                      ?>
                                  <div class="col-sm-4 col-md-2">
                                      <form method="post" action="pos.php?action=add&id=<?php echo $product['PRODUCT_ID']; ?>">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $product['NAME']; ?></h6>
                                        <h6><?php echo number_format($product['PRICE']); ?> XAF</h6>
                                              <input type="text" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="name" value="<?php echo $product['NAME']; ?>" />
                                              <input type="hidden" name="price" value="<?php echo $product['PRICE']; ?>" />
                                        <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info" value="Ajouter" />
                                          </div>
                                      </form>
                                  </div>
                                  <?php endwhile; else: ?>
                                  <div class="col-12">
                                    <p class="text-muted text-center">Aucun produit dans cette catégorie</p>
                                    </div>
                                  <?php endif; ?>
                                </div>
                              </div>

                              <!-- Tests Rapides -->
                              <div class="tab-pane fade in mt-2" id="testsrapides">
                                  <div class="row">
                                  <?php  
                                    $query = 'SELECT * FROM product WHERE CATEGORY_ID=3 GROUP BY PRODUCT_CODE ORDER by PRODUCT_CODE ASC';
                                        $result = mysqli_query($db, $query);
                                    if ($result && mysqli_num_rows($result) > 0):
                                                while($product = mysqli_fetch_assoc($result)):
                                      ?>
                                  <div class="col-sm-4 col-md-2">
                                      <form method="post" action="pos.php?action=add&id=<?php echo $product['PRODUCT_ID']; ?>">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $product['NAME']; ?></h6>
                                        <h6><?php echo number_format($product['PRICE']); ?> XAF</h6>
                                              <input type="text" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="name" value="<?php echo $product['NAME']; ?>" />
                                              <input type="hidden" name="price" value="<?php echo $product['PRICE']; ?>" />
                                        <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info" value="Ajouter" />
                                          </div>
                                      </form>
                                  </div>
                                  <?php endwhile; else: ?>
                                  <div class="col-12">
                                    <p class="text-muted text-center">Aucun produit dans cette catégorie</p>
                                    </div>
                                  <?php endif; ?>
                                </div>
                              </div>

                              <!-- Matériel chirurgical et Accouchement -->
                              <div class="tab-pane fade in mt-2" id="materielchirurgicaletaccouchement">
                                  <div class="row">
                                  <?php  
                                    $query = 'SELECT * FROM product WHERE CATEGORY_ID=4 GROUP BY PRODUCT_CODE ORDER by PRODUCT_CODE ASC';
                                        $result = mysqli_query($db, $query);
                                    if ($result && mysqli_num_rows($result) > 0):
                                                while($product = mysqli_fetch_assoc($result)):
                                      ?>
                                  <div class="col-sm-4 col-md-2">
                                      <form method="post" action="pos.php?action=add&id=<?php echo $product['PRODUCT_ID']; ?>">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $product['NAME']; ?></h6>
                                        <h6><?php echo number_format($product['PRICE']); ?> XAF</h6>
                                              <input type="text" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="name" value="<?php echo $product['NAME']; ?>" />
                                              <input type="hidden" name="price" value="<?php echo $product['PRICE']; ?>" />
                                        <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info" value="Ajouter" />
                                          </div>
                                      </form>
                                  </div>
                                  <?php endwhile; else: ?>
                                  <div class="col-12">
                                    <p class="text-muted text-center">Aucun produit dans cette catégorie</p>
                                    </div>
                                  <?php endif; ?>
                                </div>
                              </div>

                              <!-- Dispositif de chirurgie et urologie -->
                              <div class="tab-pane fade in mt-2" id="dispositifdechirurgieeturologie">
                                  <div class="row">
                                  <?php  
                                    $query = 'SELECT * FROM product WHERE CATEGORY_ID=5 GROUP BY PRODUCT_CODE ORDER by PRODUCT_CODE ASC';
                                        $result = mysqli_query($db, $query);
                                    if ($result && mysqli_num_rows($result) > 0):
                                                while($product = mysqli_fetch_assoc($result)):
                                      ?>
                                  <div class="col-sm-4 col-md-2">
                                      <form method="post" action="pos.php?action=add&id=<?php echo $product['PRODUCT_ID']; ?>">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $product['NAME']; ?></h6>
                                        <h6><?php echo number_format($product['PRICE']); ?> XAF</h6>
                                              <input type="text" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="name" value="<?php echo $product['NAME']; ?>" />
                                              <input type="hidden" name="price" value="<?php echo $product['PRICE']; ?>" />
                                        <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info" value="Ajouter" />
                                          </div>
                                      </form>
                                  </div>
                                  <?php endwhile; else: ?>
                                  <div class="col-12">
                                    <p class="text-muted text-center">Aucun produit dans cette catégorie</p>
                                    </div>
                                  <?php endif; ?>
                                </div>
                              </div>

                              <!-- Premiers soins -->
                              <div class="tab-pane fade in mt-2" id="premierssoins">
                                  <div class="row">
                                  <?php  
                                    $query = 'SELECT * FROM product WHERE CATEGORY_ID=6 GROUP BY PRODUCT_CODE ORDER by PRODUCT_CODE ASC';
                                        $result = mysqli_query($db, $query);
                                    if ($result && mysqli_num_rows($result) > 0):
                                                while($product = mysqli_fetch_assoc($result)):
                                      ?>
                                  <div class="col-sm-4 col-md-2">
                                      <form method="post" action="pos.php?action=add&id=<?php echo $product['PRODUCT_ID']; ?>">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $product['NAME']; ?></h6>
                                        <h6><?php echo number_format($product['PRICE']); ?> XAF</h6>
                                              <input type="text" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="name" value="<?php echo $product['NAME']; ?>" />
                                              <input type="hidden" name="price" value="<?php echo $product['PRICE']; ?>" />
                                        <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info" value="Ajouter" />
                                          </div>
                                      </form>
                                  </div>
                                  <?php endwhile; else: ?>
                                  <div class="col-12">
                                    <p class="text-muted text-center">Aucun produit dans cette catégorie</p>
                                    </div>
                                  <?php endif; ?>
                                </div>
                              </div>

                              <!-- Appareil de chirurgie -->
                              <div class="tab-pane fade in mt-2" id="appareildechirurgie">
                                  <div class="row">
                                  <?php  
                                    $query = 'SELECT * FROM product WHERE CATEGORY_ID=7 GROUP BY PRODUCT_CODE ORDER by PRODUCT_CODE ASC';
                                        $result = mysqli_query($db, $query);
                                    if ($result && mysqli_num_rows($result) > 0):
                                                while($product = mysqli_fetch_assoc($result)):
                                      ?>
                                  <div class="col-sm-4 col-md-2">
                                      <form method="post" action="pos.php?action=add&id=<?php echo $product['PRODUCT_ID']; ?>">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $product['NAME']; ?></h6>
                                        <h6><?php echo number_format($product['PRICE']); ?> XAF</h6>
                                              <input type="text" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="name" value="<?php echo $product['NAME']; ?>" />
                                              <input type="hidden" name="price" value="<?php echo $product['PRICE']; ?>" />
                                        <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info" value="Ajouter" />
                                          </div>
                                      </form>
                                  </div>
                                  <?php endwhile; else: ?>
                                  <div class="col-12">
                                    <p class="text-muted text-center">Aucun produit dans cette catégorie</p>
                                    </div>
                                  <?php endif; ?>
                                </div>
                              </div>

                              <!-- Autres.... -->
                              <div class="tab-pane fade in mt-2" id="autres">
                                  <div class="row">
                                  <?php  
                                    $query = 'SELECT * FROM product WHERE CATEGORY_ID=9 GROUP BY PRODUCT_CODE ORDER by PRODUCT_CODE ASC';
                                        $result = mysqli_query($db, $query);
                                    if ($result && mysqli_num_rows($result) > 0):
                                                while($product = mysqli_fetch_assoc($result)):
                                      ?>
                                  <div class="col-sm-4 col-md-2">
                                      <form method="post" action="pos.php?action=add&id=<?php echo $product['PRODUCT_ID']; ?>">
                                          <div class="products">
                                              <h6 class="text-info"><?php echo $product['NAME']; ?></h6>
                                        <h6><?php echo number_format($product['PRICE']); ?> XAF</h6>
                                              <input type="text" name="quantity" class="form-control" value="1" />
                                              <input type="hidden" name="name" value="<?php echo $product['NAME']; ?>" />
                                              <input type="hidden" name="price" value="<?php echo $product['PRICE']; ?>" />
                                        <input type="submit" name="addpos" style="margin-top:5px;" class="btn btn-info" value="Ajouter" />
                                          </div>
                                      </form>
                                  </div>
                                  <?php endwhile; else: ?>
                                  <div class="col-12">
                                    <p class="text-muted text-center">Aucun produit dans cette catégorie</p>
                                    </div>
                                  <?php endif; ?>
                                </div>
                              </div>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                      </div>
                    </div>
                  </div>